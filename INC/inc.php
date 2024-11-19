<?php
require_once "../load.php";
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
    public function inner_nav($title)
    {
        ?>


            <div class="inner_nav">

                <a href="">

                    <h3> <i class="icon-dashboard">🏠</i>Dashboard</h3>

                </a>


                <a href="">

                    <h3> <i class="icon-profile">Graph📊</i></h3>

                </a>


                <a href="../module2/request_form.php">

                    <h3><i class="icon-leave-request">Request📅</i></h3>
                </a>


                <a href="Display3.php">
                    <h3><i class="icon-statistics">Statistics📊</i></h3>
                </a>
                <?php



                if ($_SESSION["role"] == "H.R."||$_SESSION["role"] == "Admin") {
                    ?>
                    <a href="requests.php">
                        <h3><i class="icon-leave-status">Status📋</i> </h3>
                    </a>

                    <?php
                    if ($title == "Filter By Month or Name") {
                        ?>
                        <a href="filterByNameOrID.php">
                            <h3><i class="icon-leave-history">Filter By Name or ID📜</i></h3>
                        </a>
                        <?php
                    } else {
                        ?>
                        <a href="filterByMonthOrName.php">
                            <h3><i class="icon-leave-history">Filter By Month Or Name📜</i></h3>
                        </a>
                        <?php
                    }

                    if ($_SESSION["role"] == "Admin") {
                      
                       
                            ?>
                            <a href="filterByNameOrID.php">
                                <h3><i class="icon-leave-history">Filter By Name or ID📜</i></h3>
                            </a>
                            <?php
                     
                            ?>
                            <a href="filterByMonthOrName.php">
                                <h3><i class="icon-leave-history">Filter By Month Or Name📜</i></h3>
                            </a>
                            <?php
                     

                }


                ?>

                <?php


                ?>


            </div>






            <?php



    }
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