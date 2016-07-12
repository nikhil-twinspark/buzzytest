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
                        <i class="ace-icon fa fa-random"></i>
                        500
                </span>
                Something Went Wrong
        </h1>

        <hr />
        <h3 class="lighter smaller">
                But we are working
                <i class="ace-icon fa fa-wrench icon-animated-wrench bigger-125"></i>
                on it!
        </h3>

        <div class="space"></div>

        <div>
                <h4 class="lighter smaller">Meanwhile, try one of the following:</h4>

                <ul class="list-unstyled spaced inline bigger-110 margin-15">
                        

                        <li>
                                <i class="ace-icon fa fa-hand-o-right blue"></i>
                                <span onclick="do_lightbox()" style="cursor: pointer">Give us more info on how this specific error occurred!</span>
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