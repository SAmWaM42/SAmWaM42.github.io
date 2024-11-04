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

                    if (document.forms["sub_form"]["leave_type"].value == "Special")
                    
                    {
                        if (document.forms["sub_form"]["special_type"].value == "") 
                        {
                            alert("a special type must be declared");
                            return false;
                        }
                       
                       

                    }
                  
                }
            </script>
            <link rel="stylesheet" href="CSS/style.css">
            <title><?php echo $title ?></title>
        </head>

        <body>

            <?php

    }
    public function nav_bar()
    {
        ?>
            <header>
                <h1>header</h1>
            </header>
            <div>
                <h2>navigate to bar</h2>
            </div>
            <?php

    }
    public function inner_nav()
    {
        ?>

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

            <?php

    }
    public function footer()
    {
        ?>
            <footer>

                <p>additional links</p>


            </footer>
        </body>


        </html>
        <?php

    }
}