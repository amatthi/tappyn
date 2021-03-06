<?php defined("BASEPATH") or exit('No direct script access allowed');

class Notifications extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in to review notifications")->code(400)->respond();
            exit();
        }
        $this->load->library('notification');
        $this->notification->setUser($this->ion_auth->user()->row()->id);
    }

    /**
     * Retrieve the number of unread notifications for a given user
     * @return void
     */
    public function index()
    {
        $this->responder->data(array(
            'unread_notifications' => $this->notification->count()
        ))->respond();
    }

    public function unread()
    {
        $notifications = $this->notification->fetchUnread();
        if($notifications !== FALSE)
        {
            $this->responder->data(array(
                'notifications' => $notifications
            ))->respond();
        } else {
            $this->responder->fail(
                ($this->notification->errors() ? $this->notification->errors() : "An unknown error occured")
            )->code(500)->respond();
        }
    }

    public function read()
    {
        $type = $this->input->post('type');
        $object_id = $this->input->post('object_id');
        if($this->notification->markAsRead(array('type' => $type, 'object_id' => $object_id)))
        {
            $this->responder->message("Notifications marked as read")->respond();
        } else {
            $this->responder->fail("There was an error updating your notifications")->code(500)->respond();
        }
    }

    public function read_all()
    {
        $this->notification->markAsRead();
        $this->responder->data(array())->respond();
    }
}
