
<!-- Modal -->
<div id="myModal2" class="modal fade popupwidth " role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content needquestion">
      <div class="modal-header">
        <button type="button" onclick="myFunctionClose()" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title loing-logo"><img src="img/logo.png" alt="FitMakersLive" width="" height=""></h4>
		<h2>Create an account to start getting answers to your questions</h2>
      </div>
      <div class="modal-body mCustomScrollbar">
        
		<div class="col-md-12 needquestion">
	 	 <div class="row">
		  <form class="registerform" name="form" action="" method="POST" id="form" enctype="multipart/form-data">
			<div class="form-group label-floating">
                            <label class="control-label"><div class="required">First Name</div></label>
                            <input class="form-control" name="firstname" id="firstname" type="text" maxlength="50" autofocus="true" autocomplete="off">
			</div>
			<div class="form-group label-floating">
			  <label class="control-label"><div class="required">Last Name</div></label>
                          <input class="form-control" name="lastname" id="lastname" type="text" maxlength="50" autocomplete="off">
			</div>
			<div class="form-group label-floating">
			  <label class="control-label"><div class="required">Email</div></label>
                          <input class="form-control" name="email" id="email" type="email" maxlength="50" autocomplete="off">
			</div>
			<div class="form-group label-floating">
			  <label class="control-label">Choose Qualification</label>
                          <!--<input class="form-control" name="qualification" id="qualification" type="text">-->
                          <select class="form-control" name="qualification" id="qualification" onchange="myFunction()">
  <option value="">Select Qualification</option>
  <option value="GCSE">GCSE</option>
  <option value="A Level">A Level</option>
  <option value="GCE AS Level">GCE AS Level</option>
  <option value="GCE Applied A Level">GCE Applied A Level</option></select>
			</div>
			<div class="form-group label-floating">
			  <label class="control-label">Choose Subject</label>
                          <!--<input class="form-control" name="subject" id="subject" type="text">-->
                          <select class="form-control" name="subject" id="subject"  >
  <option value="">Select Subject</option></select>
			</div>
			<div class="form-group label-floating">
			  <label class="control-label">Referral Code</label>
                          <input class="form-control" name="referral_code" id="referral" type="text" maxlength="50" autocomplete="off">
			</div>
			<div class="form-group label-floating">
			  <label class="control-label"><div class="required">Password</div></label>
                          <input class="form-control" name="password" id="password" type="password" maxlength="50" autocomplete="off">
			</div>
			<div class="form-group label-floating">
			  <label class="control-label"><div class="required">Confirm Password</div></label>
                          <input class="form-control" name="re_password" id="re_password" type="password" maxlength="50" autocomplete="off">
			</div>
		  
		  <div class="col-md-12 btnlognin">
			<div class="col-md-12">
                        <!--<a href="javascript:void(0)" data-dismiss="modal" name="button" class="needhelp">Sign up</a>-->
                         <input type="submit" class="btn btn-primary" name="signup-student" value="sign up" > 
                        </div>
                        
                   <p class="footer_privacy_policy">
               By signing up you agree to our <a href="javascript:void(0)">Terms and Conditions</a> and <a href="javascript:void(0)">Privacy Policy</a>
              </p>      
                        
		  </div>
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