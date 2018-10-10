exit;
@extends('admin.adminlayouts.adminlayout')

@section('head')
	<!-- BEGIN PAGE LEVEL STYLES -->
	{{HTML::style("assets/global/plugins/bootstrap-datepicker/css/datepicker3.css")}}
	{{HTML::style("assets/global/plugins/select2/select2.css")}}
	{{HTML::style("assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css")}}
	<!-- END PAGE LEVEL STYLES -->
@stop


@section('mainarea')

			
			<!-- BEGIN PAGE HEADER-->
			<h3 class="page-title">
			{{$pageTitle}} <small>Employee List</small>
			
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="{{route('admin.dashboard.index')}}">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="{{route('admin.attendances.index')}}">Payroll</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="#">Payroll</a>
					</li>
				</ul>
			
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->

			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div id="load">
                            @if(Session::get('success'))
                                <div class="alert alert-success">{{ Session::get('success') }}</div>
                            @endif
                    </div>
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-users"></i>Payroll Summary
							</div>
							<div class="tools">
							</div>
						</div>

						<div class="portlet-body">

							<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
							<tr>
								<th> EmployeeID </th>
								<th> Leaves </th>
								<th> Basic Salary </th>
								<th> Loss Of Pay </th>
								<th> Remaining Salary </th>
								<th> Status</th>
								<th> Action </th>
							</tr>
							</thead>
							<tbody>
							 
							<?php
								//echo '<pre>'; print_r($leavetypes); echo '</pre>';
								//echo '<pre>'; print_r($cuttings); echo '</pre>';
							?>
					         @foreach ($employees as $k => $v)
							 <tr>
								<td class="text-center"> {{ $v->employeeID }} </td>
								<td class="text-center"> 
								<?php 
								$late_sal_cutting = array();
								
								if(isset($cuttings[$v->employeeID])){
									
								    foreach($cuttings[$v->employeeID] as $k1 => $v1)
								    {
									      echo $k1.' = '.$v1.'<br/>';
									      
									      $oneday_pay   = ($v->salary/30);
									      $halfday_pay =  $oneday_pay/2;
									  
										  if($k1 == 'late' && $v1 > 1)
										  {
										  	  //$sal_cutting['late'] = round($halfday_pay*$v1);
										  	  $sal_cutting['cc'] = round($halfday_pay*$v1);
										  }
										  
										  //$leavetypes[$k1]
										  if($k1 == 'casual' && $v1 > $leavetypes['casual'])
										  {
										  	  $sal_cutting['cc'] += round($oneday_pay*$v1);
										  } 
										  
										  if($k1 == 'sick' && $v1 > $leavetypes['sick'])
										  {
										  	  $sal_cutting['cc'] += round($oneday_pay*$v1);
										  } 
										  
									}
                                }
								
								//echo '<pre>'; print_r($sal_cutting); echo '</pre>';
							    ?>
							    
							    
								</td>
								<td class="text-center"> {{ $v->salary }} </td>
								<?php 
							    //if()
							    ?>
								<td class="text-center"> <?php echo $sal_cutting['cc']; ?></td>
								<td class="text-center"> <?php echo $v->salary-$sal_cutting['cc']; ?> </td>
								<td class="text-center">  </td>
								<td class="text-center">  </td>	
							 </tr>	
							 @endforeach
							</tbody>
							</table>
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			<!-- END PAGE CONTENT-->

			{{--DELETE MODAL CALLING--}}
                            @include('admin.common.delete')
             {{--DELETE MODAL CALLING END--}}

@stop


@section('footerjs')


<!-- BEGIN PAGE LEVEL PLUGINS -->
	{{ HTML::script("assets/global/plugins/select2/select2.min.js")}}
	{{ HTML::script("assets/global/plugins/datatables/media/js/jquery.dataTables.min.js")}}
	{{ HTML::script("assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js")}}
    {{ HTML::script("assets/admin/pages/scripts/table-managed.js")}}
    {{HTML::script("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js")}}
    {{HTML::script("assets/admin/pages/scripts/components-pickers.js")}}

<!-- END PAGE LEVEL PLUGINS -->

	<script>
	jQuery(document).ready(function() {
        ComponentsPickers.init();
	   TableManaged.init();
	});
	</script>

@stop
	