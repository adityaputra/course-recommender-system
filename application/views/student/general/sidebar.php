            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li <?php $path = ''; if('http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'] == base_url().$path) { echo 'class="active"';}?>>
                        <a href="<?php echo base_url();?>"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li <?php $path = 'index.php/student/course'; if('http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'] == base_url().$path) { echo 'class="active"';}?>>
                        <a href="<?php echo base_url().$path;?>"><i class="fa fa-fw fa-book"></i> Mata Kuliah</a>
                    </li>
                    <li <?php $path = 'index.php/student/registration'; if('http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'] == base_url().$path) { echo 'class="active"';}?>>
                        <a href="<?php echo base_url().$path;?>"><i class="fa fa-fw fa-file"></i> KRS</a>
                    </li>
                    <li <?php $path = 'index.php/student/scorecard'; if('http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'] == base_url().$path) { echo 'class="active"';}?>>
                        <a href="<?php echo base_url().$path;?>"><i class="fa fa-fw fa-star"></i> KHS</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-check"></i> Rekomendasi<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li <?php $path = 'index.php/student/recommendation/cbf'; if('http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'] == base_url().$path) { echo 'class="active"';}?>>
		                        <a href="<?php echo base_url().$path;?>"><i class="fa fa-fw fa-check"></i> Content-based</a>
		                    </li>
                            <li <?php $path = 'index.php/student/recommendation/icf'; if('http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'] == base_url().$path) { echo 'class="active"';}?>>
		                        <a href="<?php echo base_url().$path;?>"><i class="fa fa-fw fa-check"></i> Item-based Collaborative Filtering</a>
		                    </li>
                            <li <?php $path = 'index.php/student/recommendation/ucf'; if('http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'] == base_url().$path) { echo 'class="active"';}?>>
		                        <a href="<?php echo base_url().$path;?>"><i class="fa fa-fw fa-check"></i> User-based Collaborative Filtering</a>
		                    </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <?php // echo $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];echo " "; echo base_url().$path; exit;?> 
            <!-- /.navbar-collapse -->
        </nav>