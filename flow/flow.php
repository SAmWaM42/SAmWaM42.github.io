<?php
class flow
{
    public function form()
    {
        ?>
        <div class="submit_form">
            <form autocomplete="off" action="request_back.php" name="sub_form" method="POST"
                enctype="multipart/form-dataformdata">

                <div>
                    <h2>Type of leave</h2>

                    <div>
                        <input required type="radio" name="leave_type" value="Special" id="Special" onclick='
                           document.getElementById("period").style="Display:block";
                            document.getElementById("special_cat").style="Display:block";
                            document.getElementById("end_period").style="display:none";

                        
                           '>
                        <label for="Special">Special </label>
                    </div>
                    <div>
                        <input required type="radio" name="leave_type" value="Assigned" id="Assigned" onclick='
                           document.getElementById("period").style="Display:block";
                             document.getElementById("special_cat").style="Display:none";
                               document.getElementById("end_period").style="display:inline-block";

                           '>
                        <label for="Assigned">Asssigned</label>

                    </div>

                </div>

                
                <div id="special_cat">
                    <h2>Category of special leave</h2>
                    <div>
                        <input type="radio" name="special_type" value="Maternity" id="Maternity">
                        <label for="Maternity">Maternity </label>
                    </div>
                    <div>
                        <input type="radio" name="special_type" value="Sick" id="Sick">
                        <label for="Sick">Sick </label>
                    </div>
                    <div>
                        <input type="radio" name="special_type" value="Bereavement" id="Bereavement">
                        <label for="Bereavement">Bereavement</label>
                    </div>
                    <div>
                        <input type="radio" name="special_type" value="blank" id="blank">
                        <label for="blank">Blank</label>
                    </div>
                </div>
                <div id="period" style="display:none;">
                    <h2>Period Required</h2>
                    <div>
                        <label for="start_date">Starts </label>
                        <input type="date" name="start_date" id="start_date">

                    </div>

                    <div id="end_period">
                        <label for="end_date">Ends </label>
                        <input  type="date" name="end_date" id="end_date">

                    </div>

                </div>

                <button type="submit" onclick='
                    if (document.forms["sub_form"]["leave_type"].value == "Special")
                     {
                        if (document.forms["sub_form"]["special_type"].value == "") 
                        {
                            alert("a special type must be declared");
                            return false;
                        }
                             if (document.forms["sub_form"]["start_date"].value =="") 
                        {
                            alert("a begining for your leave must be declared for a special leave");
                            return false;
                        }
                           
                    }
                        else
                        {

                         if (document.forms["sub_form"]["end_date"].value =="") 
                        {
                            alert("a period must be declared for an assigned leave");
                            return false;
                        }
                    }
                
                
                
                
                '>Submit</button>
            </form>

        </div>
        <?php
    }

}