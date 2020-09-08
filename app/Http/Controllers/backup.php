public function excelimport(Request $request)
{      $status_mesg = true;
if($request->file('imported-file'))
{
$path = $request->file('imported-file')->getRealPath();
$data = Excel::load($path, function($reader)
{
})->get();
if(!empty($data) && $data->count())
{


foreach ($data->toArray() as $row)
{
if(!empty($row))
{
$dataArray[] =
[
'staff_central_id' => $row['staff_id'],
'date_np' => $row['date'],
'weekend_holiday' => $row['weekend_holiday'],
'public_holiday' => $row['public_holiday'],
'total_work_hour' => $row['total_work_hour'],
'total_ot_hour' => $row['total_ot_hour'],
'status' => $row['status'],
//
];
}
}
if(!empty($dataArray))


{
//                    dd($dataArray);
$last_staff_central_id = 0;
foreach($dataArray as $data){
if($data['staff_central_id']=="total"){
echo $last_staff_central_id;


}else{
AttendanceDetailModel::insert($data);
$last_staff_central_id = $data['staff_central_id'];
}
}
// AttendanceDetailModel::insert($dataArray);
$status = ($status_mesg) ? 'success' : 'error';
$mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
//                    return redirect()->route('educationcreate')->with('flash',array('status'=>$status,'mesg' => $mesg));

return back()->with('flash',array('status'=>$status,'mesg' => $mesg));

}
}
}
}