
<!-- Modal -->

<div id="myModal3" class="modal fade popupwidth " role="dialog">
  <div class="modal-dialog"> 
    <!-- Modal content-->
    <div class="modal-content needquestion">
      <div class="modal-header">
        <button type="button" onclick="myFunctionClose()" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title loing-logo"><img src="img/logo.png" alt="FitMakersLive" width="" height=""></h4>
        <h2>Create an account to start answering questions</h2>
      </div>
      <div class="modal-body mCustomScrollbar">
        <div class="col-md-12 needquestion">
          <div class="row">
            <form class="registerform" name="form" action="" method="POST" id="tutor-form" enctype="multipart/form-data">
              <h3>Personal Details</h3>
              <div class="form-group label-floating">
                <label class="control-label">
                <div class="required">First Name</div>
                </label>
                <input class="form-control" name="firstname" id="firstname" type="text" maxlength="50" autocomplete="off">
              </div>
              <div class="form-group label-floating">
                <label class="control-label">
                <div class="required">Last Name</div>
                </label>
                <input class="form-control" name="lastname" id="lastname" type="text" maxlength="50" autocomplete="off">
              </div>
              <div class="form-group label-floating">
                <label class="control-label">
                <div class="required">Mobile Phone</div>
                </label>
                <input class="form-control" name="phone"  id="phone" type="text" maxlength="50" autocomplete="off">
              </div>
              <div class="form-group label-floating">
                <label class="control-label">
                <div class="required">Email</div>
                </label>
                <input class="form-control" name="email" id="email" type="email" maxlength="50" autocomplete="off">
              </div>
              <div class="form-group label-floating">
                <label class="control-label">
                Choose Expertise
                </label>
                <select class="form-control" name="expertise" id="expertise">
                  <option value="">Select expertise</option>
                  <option value="Maths">Maths</option>
                  <option value="Physics">Physics</option>
                  <option value="Chemistry">Chemistry</option>
                </select>
              </div>
              <div class="form-group label-floating uploadfile">
                <label class="control-label">Upload CV (Optional)</label>
                <!--<input class="form-control" name="cv" id="cv" type="file" maxlength="50">-->
                <div class="bootstrap-filestyle input-group">
                    <input type="text" class="form-control " placeholder="" disabled=""> 
                    <span class="group-span-filestyle input-group-btn" tabindex="0">
                        <label for="cv" class="btn btn-primary ">
                            <span class="icon-span-filestyle glyphicon glyphicon-folder-open"></span> 
                            <span class="buttonText">Upload</span>
                            <div class="ripple-container"></div>
                        </label>
                    </span>
                </div>
                <!--<input id="cv" name="cv" class="form-control" type="file" multiple class="file-loading" maxlength="50">--> 
              </div>
              <div class="form-group label-floating">
                <label class="control-label">
                Company Name
                </label>
                <input class="form-control" name="company_name" id="company_name" type="text" maxlength="50" autocomplete="off">
              </div>
              <div class="form-group label-floating">
                <label class="control-label">
                <div class="required">Password</div>
                </label>
                <input class="form-control" name="password" id="tutor_password" type="password" maxlength="50" autocomplete="off">
              </div>
              <div class="form-group label-floating">
                <label class="control-label">
                <div class="required">Confirm Password</div>
                </label>
                <input class="form-control" name="re_password" id="re_password" type="password" maxlength="50" autocomplete="off">
              </div>
              <h3>Bank Details</h3>
              <div class="form-group label-floating">
                <label class="control-label">
                Bank Name
                </label>
                <input class="form-control" name="bank_name" id="bank_name" type="text" maxlength="50" autocomplete="off">
              </div>
              <div class="form-group label-floating">
                <label class="control-label">
                Account Number
                </label>
                <input class="form-control" name="account_no" id="account_no" type="text" maxlength="50" autocomplete="off">
              </div>
              <div class="form-group label-floating">
                <label class="control-label">
                Sort Code
                </label>
                <input class="form-control" name="sort_code" id="sort_code" type="text" maxlength="50" autocomplete="off">
              </div>
              <div class="col-md-12"> 
                <!--<div class="col-md-12"><a href="javascript:void(0)" data-dismiss="modal" class="needhelp">Sign up</a></div>-->
                <input type="submit" class="btn btn-primary" name="signup-tutor" value="sign up">
              </div>
              
              <p class="footer_privacy_policy">
               By signing up you agree to our <a href="javascript:void(0)">Terms and Conditions</a> and <a href="javascript:void(0)">Privacy Policy</a>
              </p>
          </form>
         </div>
        </div>
      </div>
      <div class="modal-footer"> 
        <p class="footer_signin">
        Already have an account ?<br />
        <a href="javascript:void(0)">Log in to continue</a>
        </p>
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--> 
      </div>
    </div>
  </div>
</div>
