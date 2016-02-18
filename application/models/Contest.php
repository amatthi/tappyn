<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contest extends MY_Model
{
    protected $errors = false;
    protected $messages = false;


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = 'contests';
        $this->order_by = 'contests.id';
        $this->order_dir = 'desc';
    }

    public function log_impression($cid)
    {
        $this->db->insert('impressions', array(
            'contest_id' => $cid,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ));
    }
    public function get($id)
    {
        $contest = $this->db->select('*')->from('contests')->where('id', $id)->limit(1)->get();
        if($contest && $contest->num_rows() == 1)
        {
            $contest = $contest->row();
            $contest->submission_count = $this->submissionsCount($contest->id);
            $contest->company = $this->db->select('*')->from('profiles')->where('id', $contest->owner)->limit(1)->get()->row();
            return $contest;
        }
        return false;
    }

    public function fetchAll($params = array(), $sort_by = 'start_time', $sort_order = 'desc', $limit = 20, $offset = false)
    {
        $this->db->select('*')->from('contests');
        if(!empty($params)) $this->db->where($params);
        $this->db->order_by($sort_by, $sort_order);
        if($offset) {
            $this->db->limit($limit, $offset);
        } else {
            $this->db->limit($limit);
        }
        $contests = $this->db->get();
        if($contests && $contests->num_rows() > 0)
        {
            $results = $contests->result();
            foreach($results as $result)
            {
                $result->submission_count = $this->submissionsCount($result->id);
                $result->company = $this->db->select('*')->from('profiles')->where('id', $result->owner)->limit(1)->get()->row();
            }
            return $results;
        }
        else if($contests && $contests->num_rows() == 0)
        {
            return array();
        }
        return false;
    }

    public function submissions($cid)
    {
        $submissions = $this->db->select('*')->from('submissions')->where('contest_id', $cid)->order_by('created_at', 'desc')->get();
        if(!$submissions)
        {
            return FALSE;
        }
        $submissions = $submissions->result();
        foreach($submissions as $submission)
        {
            $submission->owner = $this->db->select('first_name, last_name, email')->from('users')->where('id', $submission->owner)->limit(1)->get()->row();
            $submission->vote_count = rand(1,5);
        }
        return $submissions;
    }

    public function submissionsCount($contest_id)
    {
        $count = $this->db->select('COUNT(*) as count')->from('submissions')->where('contest_id', $contest_id)->get();
        if($count !== FALSE)
        {
            return $count->row()->count;
        }
        return false;
    }

    public function count($params = array())
    {
        $this->db->select("COUNT(*) as count")->from('contests');
        if(!empty($params)) $this->db->where($params);
        $count = $this->db->get();
        if($count && $count->num_rows() == 1)
        {
            return (int) $count->row()->count;
        }
        return false;
    }

    public function create($data)
    {
        if(!$this->validate())
        {
            return false;
        }

        if($this->db->insert('contests', $data))
        {
            $this->messages = 'Contest successfully created';
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function messages()
    {
        return $this->messages;
    }

    public function validate()
    {
        return true;
    }
}
