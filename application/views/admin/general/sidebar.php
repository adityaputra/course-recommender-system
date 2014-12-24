            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active">
                        <a href="<?php echo base_url();?>"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url();?>index.php/admin/course"><i class="fa fa-fw fa-book"></i> Mata Kuliah</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-graduation-cap"></i> Kompetensi Lulusan <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li>
                                <a href="<?php echo base_url();?>index.php/admin/competency/courses">Kompetensi Lulusan MK</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>index.php/admin/competency/master">Data Master</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo base_url();?>index.php/admin/student"><i class="fa fa-fw fa-group"></i> Mahasiswa</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url();?>index.php/admin/registration"><i class="fa fa-fw fa-file"></i> KRS</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url();?>index.php/admin/scorecard"><i class="fa fa-fw fa-star"></i> KHS</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>