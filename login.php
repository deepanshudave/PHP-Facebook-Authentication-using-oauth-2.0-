<?php
require 'config.php';
require 'facebook.php';

$facebook = new Facebook(array(
                'appId'  => FB_APP_ID,
                'secret' => FB_SECRET_KEY,
                'cookie' => true,
));


$session = $facebook->getSession();

$me = null;
if ($session) {
    try {
        $uid = $facebook->getUser();
        $me = $facebook->api('/me');
    } catch (FacebookApiException $e) {
        error_log($e);
    }
}


if ($me) { // LoggedIn
    $logoutUrl = $facebook->getLogoutUrl();
} else { // LogOut

    // Implementing the Extented Permissions - Leave this empty array if you dont need the extended perms
    $params = array(
                'req_perms' => 'email,user_about_me,user_activities,user_birthday,user_education_history,user_events,user_groups,user_hometown,user_interests,user_likes,user_location,user_notes,user_online_presence,user_photo_video_tags,user_photos,user_relationships,user_relationship_details,user_religion_politics,user_status,user_videos,user_website,user_work_history,read_friendlists,read_insights,read_mailbox,read_requests,read_stream,xmpp_login,ads_management,user_checkins'
            );
    $loginUrl = $facebook->getLoginUrl($params);
}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <title>PHP Oauth 2.0 authentication</title>
    </head>
    <body>
        <?php if ($me): ?>
            <a href="<?php echo $logoutUrl; ?>">
                <img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif" />
            </a>
        <?php else: ?>
            <div>
                Using PHP &amp; XFBML:
                <a href="<?php echo $loginUrl; ?>">
                    <img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif" />
                </a>
            </div>
        <?php endif ?>


        <h3>User Session Data</h3>
        <?php if ($me): ?>
            <pre><?php print_r($session); ?></pre>

            <h3>You</h3>
            <img src="https://graph.facebook.com/<?php echo $uid; ?>/picture">
            <?php echo $me['name']; ?>

            <h3>Your User Object</h3>
            <pre><?php print_r($me); ?></pre>
        <?php else: ?>
            <strong><em>You are not Connected.</em></strong>
        <?php endif ?>
    </body>
</html>