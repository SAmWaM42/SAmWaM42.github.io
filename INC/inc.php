<?php
class inc
{
    public function header($title)
    {
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <script>

                function custom_validate() {

                    if (document.forms["sub_form"]["leave_type"].value == "Special") {
                        if (document.forms["sub_form"]["special_type"].value == "") {
                            alert("a special type must be declared");
                            return false;
                        }



                    }

                }
            </script>
            <link rel="stylesheet" href="../CSS/style.css">
            <title><?php echo $title ?></title>
        </head>

        <body>

            <?php

    }
    public function nav_bar()
    {
        ?>
            <nav class="navbar">
                <div class="navbar__container">
                    <a href="#" id="navbar__logo" color:="black"> <img src="../Timeoff[1].jpg" width="65px"> TimeOff</a>
                    <div class="navbar__toggle" id="mobile-menu">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </div>
                    <ul class="navbar__menu">
                        <li class="navbar__item">
                            <a href="" class="navbar__links">
                                Home
                            </a>
                        </li>
                        <li class="navbar__item">
                            <a href="/" class="navbar__links">
                                About
                            </a>
                        </li>
                        <li class="navbar__btn">
                            <a href="/" class="button">
                                Sign Up
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <?php

    }
    public function inner_nav()
    {
        ?>
      
    <!-- Sidebar Menu -->
    <div class="sidebar">
        <div class="menu-item"><i class="icon-dashboard">🏠</i></div>
        <div class="menu-item"><i class="icon-profile">👤</i></div>
        <div class="menu-item"><i class="icon-settings">⚙️</i></div>
        <div class="menu-item"><a href="../module2/requests.php"><i class="icon-leave-request">📅</i></a></div>
        <div class="menu-item"><a href="../leave_status.php"><i class="icon-leave-status">📋</i> </a></div>
        <div class="menu-item"><a href="../admin_statistics.php"><i class="icon-statistics">📊</i> </a></div>
        <!---//add 4 more buttons set to direct to leave_request,leave_status,admin_statistics
        //add a display condition on admin statistics using js to ensure only HR and higher have this button available
        //comment the functions of your pages so people know which one to use for what 
        //special conditions do not subtract--->
    </div>
 <?php
/*
             <div class="inner_nav">

                 <a href="">

                     <h3> Dashboard</h3>

                 </a>


                 <a href="request_form.php">

                     <h3> Request</h3>

                 </a>


                 <a href="">
                     <h3> Status</h3>
                 </a>


             </div>

            
             


        ?>
            <div class="dashboard">
                <!-- Sidebar Menu -->
                <div class="sidebar">
                    <div class="menu-item"><i class="icon-dashboard">🏠</i></div>
                    <div class="menu-item"><i class="icon-profile">👤</i></div>
                    <div class="menu-item"><i class="icon-settings">⚙️</i></div>
                    //add 4 more buttons set to direct to leave_request,leave_status,admin_statistics
                    //add a display condition on admin statistics using js to ensure only HR and higher have this button
                    available
                    //comment the functions of your pages so people know which one to use for what
                    //special conditions do not subtract
                </div>
*/
               


    }
    public function footer()
    {
        ?>
                <div class="footer__container">
                    <p class="website__right"> TimeOff 2024. All rights reserved</p>
                </div>

        </body>


        </html>
        <?php

    }
}