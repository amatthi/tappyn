<?php defined("BASEPATH") or exit('No direct script access allowed');

// Add to header of your file
use FacebookAds\Api;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdCreative;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\AdCreativeFields;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Fields\ObjectStorySpecFields;
use FacebookAds\Object\Fields\ObjectStory\LinkDataFields;
use FacebookAds\Object\Fields\TargetingSpecsFields;
use FacebookAds\Object\ObjectStorySpec;
use FacebookAds\Object\ObjectStory\LinkData;
use FacebookAds\Object\TargetingSpecs;
use FacebookAds\Object\Values\AdObjectives;
use FacebookAds\Object\Values\BillingEvents;
use FacebookAds\Object\Values\OptimizationGoals;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

// Add after echo "You are logged in "

// Initialize a new Session and instantiate an Api object

// Add to header of your file

class Test extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('interest');
        $this->interest->setDatabase($this->db);
    }

    public function testarooni($id)
    {
        $this->load->model('contest');
        $this->contest->get($id);
        var_dump($this->contest->_data);
    }

    public function path()
    {
        $start_time = (new \DateTime(""))->format(DateTime::ISO8601);
        $end_time = (new \DateTime("+1 day"))->modify("+1 seconds")->format(DateTime::ISO8601);
        var_dump($start_time, $end_time, __DIR__);
    }

    public function ad()
    {
        $this->load->library('ad_lib');
        $this->ad_lib->check_unsend();
    }
    public function index()
    {
        $bi = 'act_502815969898232';
        $i = '1018237411624257';
        $s = 'd4b026673315e0be1d6123c53cf34aa2';
        $img_hash = 'e8819ada075057d7071d73d519671dfc'; // test
        $fan_id = '446303965572597';

        $fb = new Facebook([
            'app_id' => $i,
            'app_secret' => $s,
        ]);

        $helper = $fb->getRedirectLoginHelper();

        if (!isset($_SESSION['facebook_access_token'])) {
            $_SESSION['facebook_access_token'] = null;
        }

        if (!$_SESSION['facebook_access_token']) {
            $helper = $fb->getRedirectLoginHelper();
            try {
                $_SESSION['facebook_access_token'] = (string) $helper->getAccessToken();
            } catch (FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        }

        if ($_SESSION['facebook_access_token']) {
            //echo "You are logged in!";
            Api::init(
                $i, // App ID
                $s,
                $_SESSION['facebook_access_token']// Your user access token
            );
            $campaign = new Campaign(null, $bi);
            $campaign->setData(array(
                CampaignFields::NAME => 'my test' . date('H:i:s'),
                CampaignFields::OBJECTIVE => AdObjectives::LINK_CLICKS,
            ));

            $campaign->create(array(
                Campaign::STATUS_PARAM_NAME => Campaign::STATUS_PAUSED,
            ));
            //sleep(1);

            $targeting = new TargetingSpecs();
            $targeting->{TargetingSpecsFields::GEO_LOCATIONS} =
            array(
                'countries' => array('US'),
            );
            $start_time = (new \DateTime(""))->format(DateTime::ISO8601);
            $end_time = (new \DateTime("+1 day"))->modify("+1 seconds")->format(DateTime::ISO8601);

            $adset = new AdSet(null, $bi);
            $adset->setData(array(
                AdSetFields::NAME => 'My Ad Set' . date('H i s'),
                AdSetFields::OPTIMIZATION_GOAL => OptimizationGoals::REACH,
                AdSetFields::BILLING_EVENT => BillingEvents::IMPRESSIONS,
                AdSetFields::BID_AMOUNT => 2,
                AdSetFields::DAILY_BUDGET => 1500,
                AdSetFields::CAMPAIGN_ID => $campaign->id,
                AdSetFields::TARGETING => $targeting,
                AdSetFields::START_TIME => $start_time,
                AdSetFields::END_TIME => $end_time,
            ));
            $adset->create(array(
                AdSet::STATUS_PARAM_NAME => AdSet::STATUS_PAUSED,
            ));
            //sleep(1);
            for ($i = 0; $i < 1; $i++) {
                $link_data = new LinkData();
                $link_data->setData(array(
                    LinkDataFields::MESSAGE => 'try it out' . $i,
                    LinkDataFields::LINK => 'http://google.com',
                    LinkDataFields::CAPTION => 'My caption',
                    LinkDataFields::IMAGE_HASH => $img_hash,
                ));

                $object_story_spec = new ObjectStorySpec();
                $object_story_spec->setData(array(
                    ObjectStorySpecFields::PAGE_ID => $fan_id,
                    ObjectStorySpecFields::LINK_DATA => $link_data,
                ));

                $creative = new AdCreative(null, $bi);
                $creative->setData(array(
                    AdCreativeFields::NAME => 'Sample Creative',
                    AdCreativeFields::OBJECT_STORY_SPEC => $object_story_spec,
                ));

                $creative->create();
                //sleep(1);

// Finally, create your ad along with ad creative.
                // Please note that the ad creative is not created independently, rather its
                // data structure is appended to the ad group
                $data = array(
                    AdFields::NAME => $i . 'My Ad' . date('H i s'),
                    AdFields::ADSET_ID => $adset->id,
                    AdFields::CREATIVE => array(
                        'creative_id' => $creative->id,
                    ),
                );

                $ad = new Ad(null, $bi);
                $ad->setData($data);
                $ad->create(array(
                    Ad::STATUS_PARAM_NAME => Ad::STATUS_PAUSED,
                ));
                sleep(1);
            }

            //var_dump($creative);
        } else {
            $permissions = ['ads_management'];
            $loginUrl = $helper->getLoginUrl('http://tappyn.local/api/v1/test/', $permissions);
            echo '<a href="' . $loginUrl . '">Log in with Facebook</a>';
        }

    }

    // public function fetch()
    // {
    //     if($this->interest->create('asdfasaadfasdasdf', "asdfaasdfaasdfdfaasdf", 12))
    //     {
    //
    //     } else {
    //
    //     }
    //     redirect('test/tree', 'refresh');
    // }
    //
    // public function reset()
    // {
    //     $this->db->query('DELETE FROM interests; ALTER TABLE interests AUTO_INCREMENT = 1');
    // }
    //
    // public function delete($id)
    // {
    //     if($this->interest->delete($id))
    //     {
    //
    //     } else {
    //
    //     }
    //     redirect('test/tree', 'refresh');
    // }
    //
    // public function tree()
    // {
    //     echo json_encode($this->interest->tree());
    // }
    //
    // public function auth()
    // {
    //     var_dump($this->config->item('email_activation', 'ion_auth'));
    //     $this->config->set_item('email_activation', FALSE);
    //     var_dump($this->config->item('email_activation'));
    // }
}
