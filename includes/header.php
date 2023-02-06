<?php
require('config/config.php');
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");
include("includes/classes/Notification.php");

if(isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION["username"];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);
}
else {
    header("Location: register.php");
}

?>

<html>
<head>
    <title>Socialfeed</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modalmanager.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="assets/js/jquery.Jcrop.js"></script>
	<script src="assets/js/jcrop_bits.js"></script>
    <script src="assets/js/demo.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />
</head>

<body>
    <div class="top_bar">
        <div class="logo">
            <a href="index.php">Socialfeed</a>
        </div>

        <div class="search">
            <form action="search.php" method="GET" name="search_form">
                <input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input">
                <div class="button_holder">
                    <img src="assets/images/icons/magnifying_glass.png" alt="">
                </div>
            </form>
            <div class="search_results"></div>
            <div class="search_results_footer_empty"></div>
        </div>

        <nav>
            <?php
                //Unread messages 
				$messages = new Message($con, $userLoggedIn);
				$num_messages = $messages->getUnreadNumber();

				//Unread notifications 
				$notifications = new Notification($con, $userLoggedIn);
				$num_notifications = $notifications->getUnreadNumber();

                //Unread notifications 
				$user_obj = new User($con, $userLoggedIn);
				$num_requests = $user_obj->getNumberOfFriendRequests();
            ?>
            <a href="">
                <?php 
                    echo $user['first_name'];
                ?>
            </a>
            <a href="index.php">
                <i class="fa fa-home fa-lg"></i>
            </a>
            <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')">
				<i class="fa fa-envelope fa-lg"></i>
				<?php
				if($num_messages > 0)
				 echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
				?>
			</a>
            <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')">
				<i class="fa fa-bell fa-lg"></i>
				<?php
				if($num_notifications > 0)
				 echo '<span class="notification_badge" id="unread_notification">' . $num_notifications . '</span>';
				?>
			</a>
            <a href="requests.php">
                <i class="fa fa-users fa-lg"></i>
                <?php
				if($num_requests > 0)
				 echo '<span class="notification_badge" id="unread_requests">' . $num_requests . '</span>';
				?>
            </a>
            <a href="settings.php">
                <i class="fa fa-cog fa-lg"></i>
            </a>
            <a href="includes/handlers/logout.php">
                <i class="fa fa-sign-out fa-lg"></i>
            </a>
        </nav>

        <div class="dropdown_data_window" style="height: 0px; border: none;"></div>
        <input type="hidden" id="dropdown_data_type" value="">

         <script>
            $(function(){
                let userLoggedIn = '<?php echo $userLoggedIn; ?>';
                let dropdownInProgress = false;

                $(".dropdown_data_window").scroll(function() {
                    const bottomElement = $(".dropdown_data_window a").last();
                    const noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

                    // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
                    if (isElementInView(bottomElement[0]) && noMoreData == 'false') {
                        loadPosts();
                    }
                });

                function loadPosts() {
                    //If it is already in the process of loading some posts, just return
                    if(dropdownInProgress) return;
                    
                    dropdownInProgress = true;

                    //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'
                    let page = $('.dropdown_data_window').find('.nextPageDropdownData').val() || 1; 

                    let pageName; //Holds name of page to send ajax request to
                    let type = $('#dropdown_data_type').val();

                    if(type == 'notification')
                        pageName = "ajax_load_notifications.php";
                    else if(type == 'message')
                        pageName = "ajax_load_messages.php";

                    $.ajax({
                        url: `includes/handlers/${pageName}`,
                        type: "POST",
                        data: `page=${page}&userLoggedIn=${userLoggedIn}`,
                        cache:false,
                        success: function(response) {
                            $('.dropdown_data_window').find('.nextPageDropdownData').remove(); //Removes current .nextpage 
                            $('.dropdown_data_window').find('.noMoreDropdownData').remove();
                            $('.dropdown_data_window').append(response);

                            dropdownInProgress = false;
                        }
                    });
                }

                //Check if the element is in view
                function isElementInView (el) {
                    const rect = el.getBoundingClientRect();

                    return (
                        rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
                        rect.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
                    );
                }
            });
        </script>

        <div class="dropdown_data_window" style="height: 0px; border: none;">
            <input type="hidden" id="dropdown_data_type" value="">
        </div>
    </div>

    <div class="wrapper">