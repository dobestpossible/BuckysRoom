<?php
/**
* Friends Page
*/

if(!isset($BUCKYS_GLOBALS))
{
    die("Invalid Request!");
}
?>
<section id="main_section">    
        
    <!-- Left Side -->
    <?php buckys_get_panel('profile_left_sidebar') ?>
    
    <!-- 752px -->
    <section id="right_side">        
        <div class="info-box" id="friends-box">
            <h3>View All Friends <a href="/profile.php?user=<?php echo $userData['userID']?>" class="view-all">(back to profile)</a></h3>
            <?php render_result_messages(); ?>
            <?php $pagination->renderPaginate("/friends.php?user=" . $profileID . "&", count($friends)); ?>
            <div class="table userfriends" id="friends-box">
                <div class="thead">
                    <div class="td td-friend-icon">Friend</div>
                    <div class="td td-friend-info"></div>
                    <div class="td td-friend-action">Action</div>
                    <div class="clear"></div>
                </div>
                <?php
                foreach($friends as $i=>$row)
                {
                ?>
                <div class="tr <?php echo $i == count($friends) - 1 ? 'noborder' : ''?> ">
                    <div class="td td-friend-icon"><?php render_profile_link($row, 'friendIcon'); ?></div>
                    <div class="td td-friend-info">
                        <p><a href="/profile.php?user=<?php echo $row['userID']?>"><b><?php echo $row['fullName'] ?></b></a></p>                        
                        <p><?php echo $row['gender']?></p>
                        <p><?php echo $row['birthdate'] != '0000-00-00' ? date('F j, Y', strtotime($row['birthdate'])) : ""?></p>
                    </div>
                    <div class="td td-friend-action">
                        <p><a href="/profile.php?user=<?php echo $row['userID']?>">View Profile</a></p>
                        <?php if( buckys_not_null($userID) ){ ?>
                        <p><a href="/messages_compose.php?to=<?php echo $row['userID']?>">Send Message</a></p>
                        <?php } ?>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php
                }
                if(count($friends) < 1)
                {
                ?>
                <div class="tr noborder">
                    Nothing to see here.
                </div>
                <?php
                }
                ?>
            </div>
        </div>
        
        
    </section>
</section>