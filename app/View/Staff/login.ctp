
<?php $sessionstaff = $this->Session->read('staff'); ?>
<body class="login-layout light-login">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									
									<a href="<?=Staff_Name?>staff/login/" class="active logobox">
<?php echo $this->html->image(CDN.'img/lamparski_staff_new.png',array('title'=>'BuzzyDoc','alt'=>'BuzzyDoc logo','class'=>'img-responsive'));?>
                        </a>
								</h1>
								<h4 class="blue" id="id-company-text">&copy; <?=$sessionstaff['api_user']?></h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon glyphicon glyphicon-user blue"></i>
												Please Enter Your Information
                                                                                                
											</h4>

											<div class="space-6"></div>
<?php echo $this->Session->flash(); ?>  
											<?php echo $this->Form->create("login",array('class'=>'loginBox')); ?>
                                                                                          
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
					
                                        <?php echo $this->Form->input("staff_name",array('label'=>false,'class'=>'form-control','div'=>false,'placeholder'=>'User Name','required'));?>
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">

                                                                                                                        <?php echo $this->Form->input("staff_password",array('type'=>'password','label'=>false,'class'=>'form-control','div'=>false,'placeholder'=>'Password','required')); ?>
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">
														

		<!--<button type="button" class="width-35 pull-right btn btn-sm btn-primary">
		<i class="ace-icon fa fa-key"></i>
		<span class="bigger-110">Login</span></button>
  -->
                <input type="submit" value="Staff Login" class="width-35 pull-right btn btn-sm btn-primary" ><i class="ace-icon fa fa-key"></i></input>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

											

											<div class="space-6"></div>

											
										</div><!-- /.widget-main -->

										<div class="toolbar clearfix">
											<div>
												<a href="/staff/forgotpassword" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													I forgot my password
												</a>
                        
											</div>

											
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Retrieve Password
											</h4>

											<div class="space-6"></div>
											<p>
												Enter your email to receive instructions
											</p>

                     <form action="<?=Staff_Name?>staff/forgotpassword/" method="POST" name="pass_form" id="pass_form"  >
      
                            <fieldset>
                                    <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                    
                                                    <input type="email"  maxlength="50" class="form-control" placeholder="Email" required="required" id="staff_email" name="staff_email" value="">
        
                                                    <i class="ace-icon fa fa-envelope"></i>
                                            </span>
                                    </label>

                                    <div class="clearfix">
                                            
                                        <input type="submit" value="Submit" class="width-35 pull-right btn btn-sm btn-danger" onclick="return checkForgotPassword()"><i class="ace-icon fa fa-lightbulb-o"></i></input>
                                    </div>
                            </fieldset>
                    </form>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Back to login
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

							
							</div><!-- /.position-relative -->

							
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->
<a href="https://heapanalytics.com/?utm_source=badge" style="position: absolute; right: 5%; bottom: 5%; display: inline-block;"><img style="width:108px;height:41px" src="//heapanalytics.com/img/badgeLight.png" alt="Heap | Mobile and Web Analytics" /></a>
		<!-- basic scripts -->

	

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
		

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				e.preventDefault();
			 });
			 
			});
                        
                        
                        function checkForgotPassword(){
                            var staff_email=$('#staff_email').val();
                            if(staff_email==''){
                                alert('Please enter email id.');
                                return false;
                            }
                            
            $.ajax({
	  type:"POST",
	  data:"staff_email="+staff_email,
	  url:"<?=Staff_Name?>staff/forgotpassword/",
	  success:function(result){
	  if(result==0){
              alert('Your entry does not exist in our record');
              return false;
          }else{
		alert('Password is successfully sent to your email id');
		}
        }});
        return false;
        }
		</script>
                
	</body>
