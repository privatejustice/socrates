<?php

namespace Socrates\Conversations\Topic;

class InitTopicConversation extends Conversation
{
    protected $firstname;

    protected $email;

    protected $phone;

    public function run()
    {
        // This will be called immediately
        $this->askFirstname();
    }

    public function askPhoto()
    {
        $this->askForImages(
            'Please upload an image.', function ($images) {
                //
            }, function (Answer $answer) {
                // This method is called when no valid image was provided.
            }
        );
    }
    
    public function askVideos()
    {
        $this->askForVideos(
            'Please upload a video.', function ($videos) {
                // $videos is an array containing all uploaded videos.
            }
        );
    }
    
    public function askAudio()
    {
        $this->askForAudio(
            'Please upload an audio file.', function ($audio) {
                // $audio is an array containing all uploaded audio files.
            }
        );
    }
    
    public function askLocation()
    {
        $this->askForLocation(
            'Please tell me your location.', function (Location $location) {
                // $location is a Location object with the latitude / longitude.
            }
        );
    }



    public function stopsConversation(IncomingMessage $message)
    {
        if ($message->getText() == 'stop') {
            return true;
        }

        return false;
    }


    public function skipsConversation(IncomingMessage $message)
    {
        if ($message->getText() == 'pause') {
            return true;
        }

        return false;
    }
}