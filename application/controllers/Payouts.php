<?php defined("BASEPATH") or exit('No direct script access allowed');

class Payouts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->view('templates/navbar');
        $this->load->library('payout');
        $this->load->library('stripe/stripe_account_library');
        $this->load->library('stripe/stripe_transfer_library');
        if(!$this->ion_auth->logged_in())
        {
            $this->session->set_flashdata('error', 'You must be logged in to access this area');
            redirect('auth/login', 'refresh');
        }
    }

    public function debug()
    {
        echo json_encode($this->stripe_transfer_library->retrieve('tr_17e9FKLjuuo5mRdrCZ7lMGen'));
    }

    public function index()
    {
        $this->data['payouts'] = $this->payout->fetch(array('user_id' => $this->ion_auth->user()->row()->id));
        $this->load->view('payouts/index', $this->data);
    }

    public function show($pid)
    {
        $payout = $this->payout->get($pid);
        if($payout)
        {
            $payout->transfer = $this->stripe_transfer_library->retrieve($payout->transfer_id);
            $this->data['payout'] = $payout;
            $this->load->view('payouts/show', $this->data);
        } else {
            $this->session->set_flashdata('error', "That payout does not exist");
            $this->load->view('payouts/index', 'refresh');
        }
    }

    /**
     * Claim a users payout
     * @return [type] [description]
     */
    public function claim($id)
    {
        // Check that the payout exists
        $payout = $this->payout->get($id);
        if(!$payout)
        {
            $this->session->set_flashdata('error', "That payout does not exist");
            redirect('payouts/index', 'refresh');
        }
        // And that it hasnt been claimed
        if($payout->claimed == 1)
        {
            $this->session->set_flashdata('error', "This payout has been claimed already");
            redirect("payouts/show/{$id}", 'refresh');
        }
        // Chekc that have set up their accounts alread'
        $stripe_account = $this->db->select('*')->where('user_id', $this->ion_auth->user()->row()->id)->limit(1)->get('stripe_accounts');
        if(!$stripe_account || $stripe_account->num_rows() == 0)
        {
            $this->session->set_flashdata('error', "You need to set up your account first");
            redirect('accounts/details', 'refresh');
        }
        $account = $this->stripe_account_library->get($stripe_account->row()->account_id);
        if(!$account)
        {
            $this->session->set_flashdata('error', "You need to set up your account first");
            redirect('account/details', 'refresh');
        }
        // check that transfers adre enable
        if(!$account->transfers_enabled)
        {
            redirect('account/payment_methods', 'refresh');
        }
        // OK, now we can process the requested transfer
        $this->form_validation->set_rules('source_id', "Destination Account", 'required');
        if($this->form_validation->run() === TRUE)
        {
            // Preporcess
            $source_id = $this->input->post('source_id');
        }
        if($this->form_validation->run() === TRUE && ($transfer = $this->stripe_transfer_library->create($account->id, $payout->contest_id, $payout->amount, $payout->id)))
        {
            $this->session->set_flashdata('message', "Transfer {$transfer->id} successfully created for {$transfer->amount}");
            redirect("payouts/show/{$payout->id}", 'refresh');
        } else {
            $this->session->set_flashdata('error', (validation_errors() ? validation_errors() : ($this->stripe_transfer_library->errors() ? $this->stripe_account_library->errors() : false)));
            redirect("payouts/show/{$payout->id}", 'refresh');
        }
    }
}
