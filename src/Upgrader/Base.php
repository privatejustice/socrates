<?php
namespace Socrates\Chat\Upgrader;


// AUTO-GENERATED FILE -- Civix may overwrite any changes made to this file
use Socrates\Chat\ExtensionUtil as E;

/**
 * Base class which provides helpers to execute upgrade logic
 */
class Base
{

    /**
     * @var varies, subclass of this
     */
    static $instance;

    /**
     * @var Socrates\Queue_TaskContext
     */
    protected $ctx;

    /**
     * @var string, eg 'com.example.myextension'
     */
    protected $extensionName;

    /**
     * @var string, full path to the extension's source tree
     */
    protected $extensionDir;

    /**
     * @var array(revisionNumber) sorted numerically
     */
    private $revisions;

    /**
     * @var boolean
     *   Flag to clean up extension revision data in socrates_setting
     */
    private $revisionStorageIsDeprecated = false;

    /**
     * Obtain a reference to the active upgrade handler.
     */
    static public function instance()
    {
        if (!self::$instance) {
            // FIXME auto-generate
            self::$instance = new \Socrates\Chat\Upgrader(
                'socrates-chatbot',
                realpath(__DIR__ . '/../../../')
            );
        }
        return self::$instance;
    }

    /**
     * Adapter that lets you add normal (non-static) member functions to the queue.
     *
     * Note: Each upgrader instance should only be associated with one
     * task-context; otherwise, this will be non-reentrant.
     *
     * @code
     * Socrates\Chat\Upgrader_Base::_queueAdapter($ctx, 'methodName', 'arg1', 'arg2');
     * @endcode
     */
    static public function _queueAdapter()
    {
        $instance = self::instance();
        $args = func_get_args();
        $instance->ctx = array_shift($args);
        $instance->queue = $instance->ctx->queue;
        $method = array_shift($args);
        return call_user_func_array(array($instance, $method), $args);
    }

    public function __construct($extensionName, $extensionDir)
    {
        $this->extensionName = $extensionName;
        $this->extensionDir = $extensionDir;
    }

    // ******** Task helpers ********

    /**
     * Run a CustomData file.
     *
     * @param  string $relativePath the CustomData XML file path (relative to this extension's dir)
     * @return bool
     */
    public function executeCustomDataFile($relativePath)
    {
        $xml_file = $this->extensionDir . '/' . $relativePath;
        return $this->executeCustomDataFileByAbsPath($xml_file);
    }

    /**
     * Run a CustomData file
     *
     * @param string $xml_file the CustomData XML file path (absolute path)
     *
     * @return bool
     */
    protected static function executeCustomDataFileByAbsPath($xml_file)
    {
        $import = new \Socrates\Utils_Migrate_Import();
        $import->run($xml_file);
        return true;
    }

    /**
     * Run a SQL file.
     *
     * @param string $relativePath the SQL file path (relative to this extension's dir)
     *
     * @return bool
     */
    public function executeSqlFile($relativePath)
    {
        Socrates\Utils_File::sourceSQLFile(
            CIVISocrates\DSN,
            $this->extensionDir . DIRECTORY_SEPARATOR . $relativePath
        );
        return true;
    }

    /**
     * @param  string $tplFile
     *   The SQL file path (relative to this extension's dir).
     *   Ex: "sql/mydata.mysql.tpl".
     * @return bool
     */
    public function executeSqlTemplate($tplFile)
    {
        // Assign multilingual variable to Smarty.
        $upgrade = new \Socrates\Upgrade_Form();

        $tplFile = \Socrates\Utils_File::isAbsolute($tplFile) ? $tplFile : $this->extensionDir . DIRECTORY_SEPARATOR . $tplFile;
        $smarty = \Socrates\Core_Smarty::singleton();
        $smarty->assign('domainID', Socrates\Core_Config::domainID());
        Socrates\Utils_File::sourceSQLFile(
            CIVISocrates\DSN, $smarty->fetch($tplFile), null, true
        );
        return true;
    }

    /**
     * Run one SQL query.
     *
     * This is just a wrapper for Socrates\Core_DAO::executeSql, but it
     * provides syntatic sugar for queueing several tasks that
     * run different queries
     *
     * @return true
     */
    public function executeSql($query, $params = array()): bool
    {
        // FIXME verify that we raise an exception on error
        Socrates\Core_DAO::executeQuery($query, $params);
        return true;
    }

    /**
     * Syntatic sugar for enqueuing a task which calls a function in this class.
     *
     * The task is weighted so that it is processed
     * as part of the currently-pending revision.
     *
     * After passing the $funcName, you can also pass parameters that will go to
     * the function. Note that all params must be serializable.
     */
    public function addTask($title)
    {
        $args = func_get_args();
        $title = array_shift($args);
        $task = new \Socrates\Queue_Task(
            array(get_class($this), '_queueAdapter'),
            $args,
            $title
        );
        return $this->queue->createItem($task, array('weight' => -1));
    }

    // ******** Revision-tracking helpers ********

    /**
     * Determine if there are any pending revisions.
     *
     * @return bool
     */
    public function hasPendingRevisions()
    {
        $revisions = $this->getRevisions();
        $currentRevision = $this->getCurrentRevision();

        if (empty($revisions)) {
            return false;
        }
        if (empty($currentRevision)) {
            return true;
        }

        return ($currentRevision < max($revisions));
    }

    /**
     * Add any pending revisions to the queue.
     *
     * @return void
     */
    public function enqueuePendingRevisions(Socrates\Queue_Queue $queue): void
    {
        $this->queue = $queue;

        $currentRevision = $this->getCurrentRevision();
        foreach ($this->getRevisions() as $revision) {
            if ($revision > $currentRevision) {
                $title = ts(
                    'Upgrade %1 to revision %2', array(
                    1 => $this->extensionName,
                    2 => $revision,
                    )
                );

                // note: don't use addTask() because it sets weight=-1

                $task = new \Socrates\Queue_Task(
                    array(get_class($this), '_queueAdapter'),
                    array('upgrade_' . $revision),
                    $title
                );
                $this->queue->createItem($task);

                $task = new \Socrates\Queue_Task(
                    array(get_class($this), '_queueAdapter'),
                    array('setCurrentRevision', $revision),
                    $title
                );
                $this->queue->createItem($task);
            }
        }
    }

    /**
     * Get a list of revisions.
     *
     * @return (mixed|string)[] sorted numerically
     *
     * @psalm-return array<mixed|string>
     */
    public function getRevisions(): array
    {
        if (!is_array($this->revisions)) {
            $this->revisions = array();

            $clazz = new ReflectionClass(get_class($this));
            $methods = $clazz->getMethods();
            foreach ($methods as $method) {
                if (preg_match('/^upgrade_(.*)/', $method->name, $matches)) {
                    $this->revisions[] = $matches[1];
                }
            }
            sort($this->revisions, SORT_NUMERIC);
        }

        return $this->revisions;
    }

    public function getCurrentRevision()
    {
        $revision = \Socrates\Core_Bao\Extension::getSchemaVersion($this->extensionName);
        if (!$revision) {
            $revision = $this->getCurrentRevisionDeprecated();
        }
        return $revision;
    }

    private function getCurrentRevisionDeprecated()
    {
        $key = $this->extensionName . ':version';
        if ($revision = \Socrates\Core_Bao\Setting::getItem('Extension', $key)) {
            $this->revisionStorageIsDeprecated = true;
        }
        return $revision;
    }

    /**
     * @return true
     */
    public function setCurrentRevision($revision): bool
    {
        Socrates\Core_Bao\Extension::setSchemaVersion($this->extensionName, $revision);
        // clean up legacy schema version store (CRM-19252)
        $this->deleteDeprecatedRevision();
        return true;
    }

    private function deleteDeprecatedRevision(): void
    {
        if ($this->revisionStorageIsDeprecated) {
            $setting = new \Socrates\Core_Bao\Setting();
            $setting->name = $this->extensionName . ':version';
            $setting->delete();
            Socrates\Core_Error::debug_log_message("Migrated extension schema revision ID for {$this->extensionName} from socrates_setting (deprecated) to civicrm_extension.\n");
        }
    }

    // ******** Hook delegates ********

    /**
     * @see https://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_install
     *
     * @return void
     */
    public function onInstall(): void
    {
        $files = glob($this->extensionDir . '/sql/*_install.sql');
        if (is_array($files)) {
            foreach ($files as $file) {
                Socrates\Utils_File::sourceSQLFile(CIVICRM_DSN, $file);
            }
        }
        $files = glob($this->extensionDir . '/sql/*_install.mysql.tpl');
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->executeSqlTemplate($file);
            }
        }
        $files = glob($this->extensionDir . '/xml/*_install.xml');
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->executeCustomDataFileByAbsPath($file);
            }
        }
        if (is_callable(array($this, 'install'))) {
            $this->install();
        }
    }

    /**
     * @see https://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_postInstall
     *
     * @return void
     */
    public function onPostInstall(): void
    {
        $revisions = $this->getRevisions();
        if (!empty($revisions)) {
            $this->setCurrentRevision(max($revisions));
        }
        if (is_callable(array($this, 'postInstall'))) {
            $this->postInstall();
        }
    }

    /**
     * @see https://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_uninstall
     *
     * @return void
     */
    public function onUninstall(): void
    {
        $files = glob($this->extensionDir . '/sql/*_uninstall.mysql.tpl');
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->executeSqlTemplate($file);
            }
        }
        if (is_callable(array($this, 'uninstall'))) {
            $this->uninstall();
        }
        $files = glob($this->extensionDir . '/sql/*_uninstall.sql');
        if (is_array($files)) {
            foreach ($files as $file) {
                Socrates\Utils_File::sourceSQLFile(CIVICRM_DSN, $file);
            }
        }
    }

    /**
     * @see https://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_enable
     *
     * @return void
     */
    public function onEnable(): void
    {
        // stub for possible future use
        if (is_callable(array($this, 'enable'))) {
            $this->enable();
        }
    }

    /**
     * @see https://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_disable
     *
     * @return void
     */
    public function onDisable(): void
    {
        // stub for possible future use
        if (is_callable(array($this, 'disable'))) {
            $this->disable();
        }
    }

    /**
     * @return bool[]|null
     *
     * @psalm-return array{0: bool}|null
     */
    public function onUpgrade($op, Socrates\Queue_Queue $queue = null)
    {
        switch ($op) {
        case 'check':
            return array($this->hasPendingRevisions());

        case 'enqueue':
            return $this->enqueuePendingRevisions($queue);

        default:
        }
    }

}
