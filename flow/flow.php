<?php
class flow
{
    public function form()
    {
        ?>
        <div class="submit_form">
            <form autocomplete="off" onsubmit="return custom_validate()" action="request_back.php" name="sub_form" method="POST" enctype="multipart/form-dataformdata"  >
                <div>
                    <h2>Period Required</h2>
                    <div>
                        <input required type="date" name="start_date" id="start_date" >
                        <label for="start_date">Starts </label>
                    </div>
                    <div>
                        <input required type="date" name="end_date" id="end_date">
                        <label for="end_date">Ends </label>
                    </div>
                </div>
                <div>
                    <h2>Type of leave</h2>
                  
                        <div>
                            <input required type="radio" name="leave_type" value="Special" id="Special">
                            <label for="Special">Special </label>
                        </div>
                        <div>
                            <input required type="radio" name="leave_type" value="Assigned" id="Assigned">
                            <label for="Assigned">Asssigned</label>
                        </div>
                   
                </div>
                <div id="special_cat">
                    <h2>Category of special leave</h2>
                    <div>
                        <input  type="radio" name="special_type" value="Maternity" id="Maternity">
                        <label for="Maternity">Maternity </label>
                    </div>
                    <div>
                        <input type="radio" name="special_type" value="Sick" id="Sick">
                        <label for="Sick">Sick </label>
                    </div>
                    <div>
                        <input  type="radio" name="special_type" value="Bereavement" id="Bereavement">
                        <label for="Bereavement">Bereavement</label>
                    </div>
                    <div>
                        <input  type="radio" name="special_type" value="blank" id="blank">
                        <label for="blank">Blank</label>
                    </div>
                </div>

            <button type="submit">Submit</button>
            </form>
            
        </div>
        <?php
    }

}