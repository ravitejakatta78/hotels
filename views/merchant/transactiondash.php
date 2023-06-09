 <header class="page-header">
            
          </header>
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="row bg-white has-shadow">
                <!-- Item -->
                <div class="col-xl-4 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa fa-database"></i></div>
                    <div class="title"><span>Total Products</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 25%; height: 4px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-violet"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?= $productCount ?></strong></div>
                  </div>
                </div>
                <!-- Item -->
                <div class="col-xl-4 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-red"><i class="fa fa-list-alt"></i></div>
                    <div class="title"><span>Total Orders</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 70%; height: 4px;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?= $orderCount; ?></strong></div>
                  </div>
                </div>
                <!-- Item -->
                <div class="col-xl-4 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-green"><i class="fa fa-user"></i></div>
                    <div class="title"><span>Employee</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 40%; height: 4px;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-green"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?= $empCount?></strong></div>
                  </div>
                </div>
                <!-- Item -->
                
              </div>
            </div>
          </section>
           <section class="dashboard-header pb-0">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-12">
                  <div class="bg-white p-4">
                    <form class="form-inline mb-0" method="POST" action="transcationdashboard">
                      <div class="form-group mr-3">
                  <input type="text" class="form-control datepicker1" name="sdate" placeholder="Start Date" value="<?= $sdate ; ?>">
                      </div>
                      <div class="form-group mr-3">
                  <input type="text" class="form-control datepicker2" name="edate" placeholder="End Date" value="<?= $edate ; ?>">
                      </div>
                      <div class="form-group">
                        <button class="btn btn-add">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <section class="dashboard-header">
		   <?php 
		  $statsCntKeys = array_values(array_keys($ordStatusCount));
		  
		  ?>
            <div class="container-fluid">
              <div class="row">
                <!-- Statistics -->
                <div class="statistics col-lg-3 col-12">
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-red"><i class="fa fa-calendar"></i></div>
                    <div class="text"><strong><?= array_sum(array_values($ordStatusCount))?></strong><br><small>Today Orders</small></div>
                  </div>
                </div>
                <div class="statistics col-lg-3 col-12">
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-green"><i class="fa fa-thumbs-o-up"></i></div>
                    <div class="text"><strong><?php if(in_array('1',$statsCntKeys)){
					echo 	$ordStatusCount['1'] ;
					}else echo 0;?></strong><br><small>Accepted Orders</small></div>
                  </div>
                </div>
                <div class="statistics col-lg-3 col-12">
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-orange"><i class="fa fa-check"></i></div>
                    <div class="text"><strong><?php if(in_array('4',$statsCntKeys)){
					$suuOrder =  	$ordStatusCount['4'] ;
					};?><?php if(in_array('2',$statsCntKeys)){
					$accOrder =  	$ordStatusCount['2'] ;
					};
					echo ($suuOrder ?? 0 ) +($accOrder ?? 0)
					?></strong><br><small>Success Orders</small></div>
                  </div>
                </div>
                <div class="statistics col-lg-3 col-12">
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-violet"><i class="fa fa-ban"></i></div>
                    <div class="text"><strong><?php if(in_array('3',$statsCntKeys)){
					echo 	$ordStatusCount['3'] ;
					}else echo 0;?></strong><br><small>Cancelled Orders</small></div>
                  </div>
                </div>
                <div class="statistics col-lg-3 col-12 mt-3">
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-purple" style="background: #CB6AEE;"><i class="fa fa-clock-o"></i></div>
                    <div class="text"><strong><?php if(in_array('0',$statsCntKeys)){
					echo 	$ordStatusCount['0'] ;
					}else echo 0;?></strong><br><small>Pending Orders</small></div>
                  </div>
                </div>
                <!-- Line Chart            -->
                
                
              </div>
            </div>
          </section>