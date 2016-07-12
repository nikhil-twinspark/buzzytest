

   
   
   
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
					
                                        <?php echo $this->Form->input("admin_name",array('label'=>false,'class'=>'form-control','div'=>false,'placeholder'=>'User Name','required'));?>
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">

                                                                                                                        <?php echo $this->Form->input("admin_password",array('type'=>'password','label'=>false,'class'=>'form-control','div'=>false,'placeholder'=>'Password','required')); ?>
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">
														

		<!--<button type="button" class="width-35 pull-right btn btn-sm btn-primary">
		<i class="ace-icon fa fa-key"></i>
		<span class="bigger-110">Login</span></button>
  -->
                <input type="submit" value="Admin Login" class="width-35 pull-right btn btn-sm btn-primary" ><i class="ace-icon fa fa-key"></i></input>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

											

											<div class="space-6"></div>

											
										</div><!-- /.widget-main -->

										
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								

							
							</div><!-- /.position-relative -->

							
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->
   