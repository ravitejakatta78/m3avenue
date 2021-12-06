<?php
include("../../functions.php");
session_start();

if(!empty($_POST['action'])){
    $action = $_POST['action'];
    switch($action){
        case 'updatevendorpopup':
            $sql = runQuery("select * from df_vendor where ID = '".$_POST['id']."'");
            $resp = !empty($sql) ? $sql : [];
            echo json_encode($resp);
        break;
        case 'hireview':
            $sql = runloopQuery("select * from hire where induction_id = '".$_POST['inductionid']."'");
            echo json_encode($sql);
        break;
        case 'getpointvariant':
            $sql = runloopQuery("select * from points_set_variant where points_id = '".$_POST['pointsetid']."'");
            echo json_encode($sql);
        break;
        case 'getvariantlocation':
            $sql = runloopQuery("select * from points_set_variant where points_id = '".$_POST['pointsetid']."' and variant = '".$_POST['variantpopupid']."'");
            echo json_encode($sql);
        break;
        case 'employeechangestatus':

            $response =array();
            $empid = $_POST['empid'];

            $super_admin_id = current_adminid(); 
            $super_admin_unique_id = employee_details($super_admin_id,'unique_id');


            $user_unique_id = employee_details($empid,'unique_id');
            $roderwharray['ID'] = $empid;
            $role_id = employee_details($empid,'role_id');
            $empdet = runQuery("select * from employee where ID = '".$empid."'");


            $subscription_details = runQuery("select * from tbl_super_admin_details where super_admin_id = '".$super_admin_id."'");


            $count_managers=runQuery("select count(*) as count from employee where status=1 and role_id=3 and  leader = '".$super_admin_unique_id."'");
            $count_executives=runQuery("select count(*) as count from employee where status=1 and role_id=4 and leader = '".$super_admin_unique_id."'");
            if($empdet['status']=='0')
            {
                if($role_id=='3' && $count_managers['count']>=$subscription_details['no_of_active_managers'])
                {
                    $response['status']=false;
                    $response['message']="You have already reached Maximum Number of Active Managers Count";
                    echo json_encode($response);
                    exit;
                }
                else if($role_id == '4' && $count_executives['count']>=$subscription_details['no_of_active_executives'])
                {
                    $response['status']=false;
                    $response['message']= "You have already reached Maximum Number of Active Executives Count";
                    echo json_encode($response);
                    exit;
                }
            }
                if($role_id == '4'){
                    

                }
                else if($role_id == '3')
                {
                    $team_members = runloopQuery("select ID from employee where leader = '".$empdet['unique_id']."'");
                    if(!empty($team_members))
                    {
                        $team_mem_arr = array_column($team_members,'ID');
                        $team_mem_id = implode("','",$team_mem_arr);
                        $teamleaderupdate = "update  employee set leader = '".$empdet['leader']."'  WHERE ID in ('".$team_mem_id."')";
                        $conn->query($teamleaderupdate);
                    }   
                }
                if($empdet['status']=='1'){
                    $roderarray['status'] ='0';
                }else{
                    $roderarray['status'] = '1';
                }
                updateQuery($roderarray,'employee',$roderwharray);
                $response['status']=true;
                $response['message']='Employee Status Successfully Changed';
                echo json_encode($response);
                exit;
            break;
            case 'get_df_application_details':
                $row = runQuery("SELECT dva.*,dv.vendor_name,dv.vendor_location FROM df_vendor_application dva inner join 
                df_vendor dv on dv.ID = dva.vendor_id  where dva.ID = '".$_POST['application_id']."'");
                $sqloutstanding = runQuery("select balance_amount,pending_amount,edi 
                from df_transaction_details where application_id ='".$row['ID']."'  order by ID desc limit 1");
                $marr['principal_amount'] = $row["principal_amount"];
                $marr['tenure'] = $row["tenure"];
                $marr['intrest'] = $row["intrest"];
                $marr['pf'] = $row["pf"];
                $marr['net_amount'] = $row["net_amount"];
                $marr['edi'] = $sqloutstanding["edi"];
                $marr['balance_amount'] = $sqloutstanding["balance_amount"];
                $marr['mode_of_payment'] = $row["mode_of_payment"];
                $marr['transaction_id'] = $row["transaction_id"];
                $marr['status'] = $row["status"] == 1 ? 'Active' : 'Closed' ;
                echo json_encode($marr);
        break;    
        default:
        echo json_encode([]);
        break; 
    }

}
else{
    echo json_encode([]);
}

?>