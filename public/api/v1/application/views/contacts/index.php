<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
        <div class='row'>
            <div class='col-sm-10 col-sm-offset-1 content'>
                <h2>Support Tickets</h2>
                <hr>
                <div class='col-sm-6 col-sm-offset-3'>
                    <?php $this->load->view('templates/notification', array(
                'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
                'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
                )); ?>
                </div>
                <div class='col-sm-12'>
                    <div class='paging-container' style='float:right'>
                        <?php echo $pagination_links; ?>
                    </div>
                    <table class='table table-condensed table-bordered table-hover table-striped'>
                        <tr>
                            <th>Actions</th>
                            <th class='sort_header' id='first_name'>First Name<?php echo is_sorted('first_name'); ?></th>
                            <th class='sort_header' id='last_name'>Last Name<?php echo is_sorted('last_name'); ?></th>
                            <th class='sort_header' id='created_on'>Sign Up<?php echo is_sorted('created_on'); ?></th>
                            <th class='sort_header' id='last_login'>Last Login<?php echo is_sorted('last_login'); ?></th>
                            <th class='sort_header' id='email'>Email<?php echo is_sorted('email'); ?></th>
                            <th>Groups</th>
                            <th class='sort_header' id='age'>Age<?php echo is_sorted('age'); ?></th>
                            <th class='sort_header' id='gender'>Gender<?php echo is_sorted('gender'); ?></th>
                        </tr>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="dropdown">
                                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Actions
                                        <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li><a href="<?php echo base_url().'admin/users/show/'.$user->id; ?>">View</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#">Deactivate</a></li>
                                      </ul>
                                    </div>
                                </td>
                                <td><?php echo $user->first_name; ?></td>
                                <td><?php echo $user->last_name; ?></td>
                                <td><?php echo date('D M d', $user->created_on); ?></td>
                                <td><?php echo date('D M d', $user->last_login); ?></td>
                                <td><?php echo $user->email; ?></td>
                                <td>
                                    <?php foreach($user->groups as $group): ?>
                                        <?php echo $group->name; ?>
                                    <?php endforeach; ?>
                                </td>
                                <td><?php echo $user->profile->age; ?></td>
                                <td><?php echo $user->profile->gender; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
<script>
$(document).ready(function(e) {
    var getVars = getUrlVars();
    console.log(getVars);
    $('.sort_header').click(function(f) {
        getVars.sort_by = $(this).attr('id');
        if(getVars.sort_dir && getVars.sort_dir == 'desc')
        {
            getVars.sort_dir = 'asc';
        }
        else
        {
            getVars.sort_dir = 'desc';
        }

        getVars.per_page = 1;
        document.location.href = "<?php echo base_url().'admin/contacts/index?'; ?>"+buildQuery(getVars);
    });

    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
        function(m,key,value) {
          vars[key] = value;
        });
        return vars;
    }

    function buildQuery(vars)
    {
        var params = [];
        for(var d in vars)
        {
            params.push(encodeURIComponent(d)+"="+encodeURIComponent(vars[d]));
        }
        return params.join('&');
    }
})
</script>
<?php $this->load->view('templates/footer'); ?>
