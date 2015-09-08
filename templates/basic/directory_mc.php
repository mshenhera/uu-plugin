<?php
    global $xoouserultra;

    $module ="";
    $act="";
    $gal_id= "";

    if(isset($_GET["module"]))
    {
        $module = $_GET["module"];
    }

    if(isset($_GET["act"]))
    {
        $act = $_GET["act"];
    }

    if(isset($_GET["gal_id"]))
    {
        $gal_id = $_GET["gal_id"];
    }

    $display_default_search = true;
?>

<?php echo $xoouserultra->userpanel->uultra_search_form(array('filters'=>'region,city', 'custom_form'=>'o0f1m', 'users_are_called'=>NULL)) ?>

<?php if (isset($users_list['paginate'])) { ?>
    <div class="usersultra-paginate bottom_display"><?php echo $users_list['paginate']; ?></div>
<?php } ?>

<?php if ($display_total_found=='yes') {
    echo $total_f;
} ?>

<div class="mc-body">
<?php foreach($users_list['users'] as $user):
    $user_id = $user->ID;
    $display_name = $xoouserultra->userpanel->get_display_name($user_id);
    $user_logo = $xoouserultra->userpanel->get_user_pic( $user_id, 125, $pic_type, 'rounded', $pic_size_type);
    $prof_category = wp_strip_all_tags(get_user_meta($user_id, 'prof_category', true), true);
    $prof_category_array = explode(',', $prof_category);
    $region = wp_strip_all_tags(get_user_meta($user_id, 'region', true), true);
    $city = wp_strip_all_tags(get_user_meta($user_id, 'city', true), true);
    $phone = wp_strip_all_tags(get_user_meta($user_id, 'phone', true), true);
    $rating = $xoouserultra->rating->get_rating($user_id,"user_id");
    $brief_description = wp_strip_all_tags(get_user_meta($user_id, 'brief_description', true), true);
    $display_latest_photos_howmany = 5;
    $site_url = site_url()."/";
    $profile_url = $site_url."profile/".$user_id."/";
    $upload_folder =  $xoouserultra->get_option('media_uploading_folder');
    $rows = $xoouserultra->photogallery->get_user_photos($user_id, $display_latest_photos_howmany);
    $prof_category_0 = wp_strip_all_tags(get_user_meta($user_id, 'prof_category_0', true), true);
    $prof_category_array_0 = explode(',', $prof_category_0);
    $prof_category_1 = wp_strip_all_tags(get_user_meta($user_id, 'prof_category_1', true), true);
    $prof_category_array_1 = explode(',', $prof_category_1);
    $prof_category_2 = wp_strip_all_tags(get_user_meta($user_id, 'prof_category_2', true), true);
    $prof_category_array_2 = explode(',', $prof_category_2);
    $prof_category_3 = wp_strip_all_tags(get_user_meta($user_id, 'prof_category_3', true), true);
    $prof_category_array_3 = explode(',', $prof_category_3);
    $prof_category_4 = wp_strip_all_tags(get_user_meta($user_id, 'prof_category_4', true), true);
    $prof_category_array_4 = explode(',', $prof_category_4);
    $prof_category_5 = wp_strip_all_tags(get_user_meta($user_id, 'prof_category_5', true), true);
    $prof_category_array_5 = explode(',', $prof_category_5);
    $prof_category_array_x = array_merge($prof_category_array_0, $prof_category_array_1, $prof_category_array_2, $prof_category_array_3, $prof_category_array_4, $prof_category_array_5);
?>
<div class="search-box">
    <div class="row row-main">
        <div class="w-logo">
            <?php echo $user_logo ?>
        </div>
        <div class="w-05col">
            <div class="row">
                <div class="w-003col">
                    <div class="box-body box-40">
                        <span class="font-title info-item title"><?php echo $display_name ?></span>
                        <span class="font-sub-title info-item sub-title"><?php echo $city ?>, <?php echo $region ?></span>
                    </div>
                </div>
                <div class="w-002col ratebox"><?php echo $rating ?></div>
            </div>
            <div class="row">
                <div class="w-001col">
                    <div class="box-body">
                        <span class="font-phone info-item title"><?php echo $phone?></span>
                        <a href="<?php echo $profile_url ?>"><span class="font-view-profile info-item title">View Profile</span></a>
                    </div>
                </div>
                <div class="w-002col">
                    <div class="font-category box-body box-profile padding-profile">
                        <ul class="category-list">
                        <?php $index = 0;
                            foreach ($prof_category_array as $category):
                                $index = $index + 1;
                                if ($category and $index == 3): ?>
                            <li><?php echo $category ?> ...</li>
                                <?php break; elseif ($category): ?>
                            <li><?php echo $category ?></li>
                        <?php endif;
                            endforeach ?>
                        </ul>
                    </div>
                </div>
                <div class="w-002col">
                    <div class="font-category box-body box-profile padding-profile-2">
                        <ul class="category-list">
                        <?php $index = 0;
                            foreach ($prof_category_array_x as $sub_category):
                                $index = $index + 1;
                                if ($sub_category and $index == 3): ?>
                            <li class="font-category"><?php echo $sub_category ?> ...</li>
                                <?php break; elseif ($sub_category): ?>
                            <li class="font-category"><?php echo $sub_category ?></li>
                        <?php endif;
                            endforeach ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-feature">
        <div class="w-col-feature1">
            <div class="box-body box-60">
<?php
    if ( empty( $rows ) ):
    else:
        foreach ( $rows as $photo ):
            $file = $photo->photo_thumb;
            $thumb = $site_url.$upload_folder."/".$user_id."/".$file;
            $photo_link = $xoouserultra->userpanel->public_profile_get_photo_link($photo->photo_id, $user_id);
?>
                <a class="mc-search-gallery-link" href="<?php echo $photo_link ?>"><img class="mc-search-gallery-img" src="<?php echo $thumb ?>"/></a>
<?php
        endforeach;
    endif;
?>
            </div>
        </div>
        <div class="w-col-feature2">
            <div class="box-body box-60">
                <div class="font-description box-content"><?php echo $brief_description ?></div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<div class="end-search-box"></div>

<?php if (isset($users_list['paginate'])) { ?>
<div class="usersultra-paginate bottom_display"><?php echo $users_list['paginate']; ?></div>
<?php } ?>

