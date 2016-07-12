<!-- #section:pages/error -->
<?php  
                //echo $this->Html->css(CDN.'css/style.css');
                echo $this->Html->css(CDN.'css/assets/css/bootstrap.min.css');
            
                echo $this->Html->css(CDN.'css/assets/css/font-awesome.min.css');
                echo $this->Html->css(CDN.'css/assets/css/ace-fonts.css');
                echo $this->Html->css(CDN.'css/assets/css/ace.min.css');
                echo $this->Html->css(CDN.'css/assets/css/ace-skins.min.css');
                echo $this->Html->css(CDN.'css/assets/css/ace-rtl.min.css');
                echo $this->Html->script(CDN.'js/assets/js/ace-extra.min.js');
                echo $this->Html->script(CDN.'js/assets/js/jquery.min.js');
                echo $this->Html->script(CDN.'js/jquery.validate.js');
            
                
                ?>
<div class="error-container">
        <div class="well">
                <h1 class="grey lighter smaller">
                        <span class="blue bigger-125">
                                <i class="ace-icon fa fa-sitemap"></i>
                                404
                        </span>
                        Page Not Found
                </h1>

                <hr />
                <h3 class="lighter smaller">We looked everywhere but we couldn't find it!</h3>

                <div>
                      

                        <div class="space"></div>
                        <h4 class="smaller">Try one of the following:</h4>

                        <ul class="list-unstyled spaced inline bigger-110 margin-15">
                                <li>
                                        <i class="ace-icon fa fa-hand-o-right blue"></i>
                                        Re-check the url for typos
                                </li>


                                <li>
                                        <i class="ace-icon fa fa-hand-o-right blue"></i>
                                        <span onclick="do_lightbox()" style="cursor: pointer">Tell us about it</span>
                                </li>
                        </ul>
                </div>

                <hr />
                <div class="space"></div>

                <div class="center">
                        <a href="javascript:history.back()" class="btn btn-grey">
                                <i class="ace-icon fa fa-arrow-left"></i>
                                Go Back
                        </a>

                        
                </div>
        </div>
</div>
<?php //echo $this->element('footer_lightbox'); ?>

								<!-- /section:pages/error -->

								<!-- PAGE CONTENT ENDS -->
								