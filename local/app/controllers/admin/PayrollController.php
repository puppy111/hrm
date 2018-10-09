<?php
/*
 * Attendance Controller of Admin Panel
 */
class PayrollController extends \AdminBaseController {


    public function __construct()
    {
        parent::__construct();
        $this->data['payrollOpen']     = 'active open';
        $this->data['pageTitle']       = 'Payroll';
    }


/*
 * This is the view page of attendance.
 */
 	public function index()
	{
		//$this->data['attendances']          =   Attendance::all();
        //$this->data['viewAttendanceActive'] =   'active';
        //$this->data['date']     = date('Y-m-d');
        //$this->data['employees']            =   Employee::where('status','=','active')->get();
        
        $this->data['employees']              =    DB::table('employees')
            									   ->leftJoin('salary', 'employees.employeeID', '=', 'salary.employeeID')
            									   ->where('employees.status','=','active')
            									   ->get();
            									   //->toArray()
        
       //echo '<pre>'; print_r($this->data['employees']); echo '</pre>';	exit;
       //$this->data['leaves'] = PayRoll::absentEveryEmployee();
	   //Office Working Hours
		
		$offc_in_time    =  strtotime('09.30 AM'); 
		$offc_out_time   =  strtotime('06.00 PM'); 
		
		$date           =   date('d');
        $month          =   date('m');
        $year           =   date('Y');
		

		$employees            =   Employee::where('status','=','active')->get();
	    $absentess            =   [];
	    $data2                = array();
		
        foreach($employees  as $employee)
        {		
			
			$Attendance           =   Attendance::select('*')
											->where('status','=','absent')
											->orWhere('status','=','present')
											//->where('employeeID','=',$employee->employeeID)
											->whereYear('date','=',$year)
											->whereMonth('date','=',$month)
											->get();
		}	
	    //echo '<pre>'; print_r($Attendance->toArray()); echo '</pre>'; exit;
			 							  							          
		foreach($Attendance as $Abs)
		{
		    if($Abs->status == 'present')
		    {
				//if($offc_in_time < strtotime($Abs->in_time.' AM'))
				if($offc_in_time < strtotime($Abs->in_time))
				{
					$this->data1['present'][$Abs->employeeID]['late'][] = $Abs->in_time;
					$this->data['a']['latecounts'][$Abs->employeeID]['late'] = 
					count($this->data1['present'][$Abs->employeeID]['late']);
				}
			}
			
			if($Abs->status == 'absent')
		    {
				$this->data1['absent'][$Abs->employeeID][$Abs->leaveType][] = $Abs->leaveType;
				$this->data['a']['absentcounts'][$Abs->employeeID][$Abs->leaveType] = 
				count($this->data1['absent'][$Abs->employeeID][$Abs->leaveType]);
			}
		}
		
		
		
		foreach($this->data['a'] as $k3)
		{
			//echo '<pre>'; print_r($k3); echo '</pre>'; exit;
			foreach($k3 as $k4 => $v3)
			{
				foreach($v3 as $k5 => $v5)
			    {
			    	$this->data['cuttings'][$k4][$k5]  = $v5;
				}
			}	
		}
		
        //echo '<pre>'; print_r($this->data['employees']); echo '</pre>';	exit;
        //echo '<pre>'; print_r($this->data['cuttings']); echo '</pre>';	exit;
		//echo '<pre>'; print_r($this->data['a']); echo '</pre>'; exit;		
		return View::make('admin.payroll.index', $this->data);
	} 
	
	/**
	 * Remove the specified attendance from storage.
	 */
	public function destroy($id)
	{
		Attendance::destroy($id);
		return Redirect::route('admin.payroll.index');
	}
	 
	
   public function importExcel()	
   { 
	    $insert1 = array();
		 
		if(Input::hasFile('import_file'))
		{			
			$path = Input::file('import_file')->getRealPath();	
					
			/*$data = Excel::load($path, function($reader) 
			{			
			})->get();*/
			
			
			$data =  Excel::load($path, function($reader) {})
                	//->skip(9) 
                	->get();
                
		   //echo '<pre>'; print_r($data->toArray()); echo '</pre>'; exit;
			
			
			if(!empty($data) && $data->count())
			{				
				foreach ($data as $key => $value) 
				{	
					
					//echo '<pre>'; print_r($value->toArray()); echo '</pre>'; 
					//$intime_arry  = date('h:i A', strtotime($value->intime->date));
					
					//$outtime_arry = explode(" ", $value->outtime->date);
					//echo $intime_arry.'=='; exit;
					//echo $value->outtime->date.'==';
					//exit;
					
					//if($value->ans1 != NULL) 
					//{					
						$insert1[] = [
						'employeeID'               => $value->e_code,
						'date'                     => '2018-10-02',
						'shift'                    => $value->shift,
						'leaveType'                => $value->leavetype,
						'in_time'                  => $value->intime,
					    'out_time'                 => $value->outtime,
						'work_dur'                 => $value->work_dur,
						'ot'                       => $value->ot,
						'total_dur'                => $value->tot_dur,
						'status'                   => $value->status,
						'status_xls'               => $value->status,
						'reason'                   => $value->remarks 
						];								
					//}				
				}				
				if(!empty($insert1))
				{					
                    //echo '<pre>'; print_r($insert1); echo '</pre>'; //exit;
					DB::table('attendance')->insert($insert1);
					return response()->json(["status" => "Success"]);
					//echo 'Insert Record successfully.';				
				}			
		    }	
		}		
		//return back();		
   }
}
