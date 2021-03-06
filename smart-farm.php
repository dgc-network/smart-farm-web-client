<?php

/**
 * Plugin Name: smart-farm
 * Plugin URI: https://wordpress.org/plugins/smart-farm/
 * Description: The leading web api plugin for pig system by shortcode
 * Author: dgc.network
 * Author URI: https://dgc.network/
 * Version: 1.0.0
 * Requires at least: 4.4
 * Tested up to: 5.2
 * 
 * Text Domain: smart-farm
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

include_once dirname( __FILE__ ) . '/php-OP_RETURN/OP_RETURN.php';
include_once dirname( __FILE__ ) . '/vendor/autoload.php';
include_once dirname( __FILE__ ) . '/build/gen/GPBMetadata/PikePayload.php';
include_once dirname( __FILE__ ) . '/build/gen/GPBMetadata/PikeState.php';
include_once dirname( __FILE__ ) . '/build/gen/Agent.php';
include_once dirname( __FILE__ ) . '/build/gen/AgentList.php';
include_once dirname( __FILE__ ) . '/build/gen/PikePayload.php';
include_once dirname( __FILE__ ) . '/build/gen/PikePayload/Action.php';
include_once dirname( __FILE__ ) . '/build/gen/CreateAgentAction.php';
include_once dirname( __FILE__ ) . '/build/gen/UpdateAgentAction.php';
include_once dirname( __FILE__ ) . '/build/gen/KeyValueEntry.php';

add_shortcode( 'agent_shortcode', 'agent_shortcode_callback' );
function agent_shortcode_callback() {

    $AgentList = new AgentList();
    $Agent = new Agent();
    
    if( isset($_POST['edit_agent']) ) {
    //if ($_GET['_mode']=='edit_agent'){

        $agents = $AgentList->getAgents();
        foreach ($agents as $index => $agent) {
            if ($_POST['_item']==$index) {
                $PublicKey = $agents[$index]->getPublicKey();
                $KeyValueEntries = $agents[$index]->getMetadata();
                foreach ($KeyValueEntries as $KeyValueEntry)
                if ($KeyValueEntry->getKey()=='email') 
                    $LoginName = $KeyValueEntry->getValue();
            }
        }

        $output  = '<form method="post">';
        $output .= '<figure class="wp-block-table"><table><tbody>';
        $output .= '<tr><td>'.'PublicKey:'.'</td><td style="width: 100%"><input style="width: 100%" type="text" name="_PublicKey" value="'.$PublicKey.'"></td></tr>';
        //$output .= '<tr><td>'.'LoginName:'.'</td><td><input style="width: 100%" type="text" name="_LoginName" value="'.$LoginName.'"></td></tr>';
        $output .= '<tr><td>'.'LoginName:'.'</td><td><input style="width: 100%" type="text" name="_LoginName" value="'.$_POST['_item'].'"></td></tr>';
        $output .= '</tbody></table></figure>';

        $output .= '<div class="wp-block-buttons">';
        $output .= '<div class="wp-block-button">';
        $output .= '<input class="wp-block-button__link" type="submit" value="OK" name="create_agent">';
        $output .= '</div>';
        $output .= '<div class="wp-block-button">';
        $output .= '<input class="wp-block-button__link" type="submit" value="Cancel"';
        $output .= '</div>';
        //$output .= '<div class="wp-block-button">';
        //$output .= '<a class="wp-block-button__link" href="?_mode='.$_GET['_submit'].'_agent">'.$_GET['_submit'].'</a>';
        //$output .= '</div>';
        //$output .= '<div class="wp-block-button">';
        //$output .= '<a class="wp-block-button__link" href="?_mode=agents_list"">Cancel</a>';
        //$output .= '</div>';
        $output .= '</div>';
        $output .= '</form>';
    
        return $output;

    }
    
    if( isset($_POST['create_agent']) ) {
    //if ($_GET['_mode']=='create_agent'){

        $Roles = array();
        $KeyValueEntries = array();

        $KeyValueEntry = new KeyValueEntry();
        $KeyValueEntry->setKey('email');
        $KeyValueEntry->setValue($_POST['_LoginName']);
        $KeyValueEntries[]=$KeyValueEntry;

        $CreateAgentAction = new CreateAgentAction();
        $CreateAgentAction->setOrgId($_GET['_OrgId']);
        $CreateAgentAction->setPublicKey($_POST['_PublicKey']);
        $CreateAgentAction->setActive($_GET['_Active']);
        $CreateAgentAction->setRoles($Roles);
        $CreateAgentAction->setMetadata($KeyValueEntries);

        $send_data = $CreateAgentAction->serializeToString();
        $send_address = 'DFcP5QFjbYtfgzWoqGedhxecCrRe41G3RD';
        $private_key = 'L44NzghbN6UD737kG6ukfdCq6BXyyTY2W15UkNhHnBff6acYWtsZ';
        $send_amount = 0.001;
    
        try {
            $agents = $AgentList->getAgents();
            $Agent->mergeFromString($send_data);
            $agents[] = $Agent;
            $AgentList->setAgents($agents);
            //$send_data = $AgentList->serializeToString();
        } catch (Exception $e) {
            // Handle parsing error from invalid data.
            // ...
        }
/*
	    $result = OP_RETURN_send($send_address, $send_amount, $send_data);
	
	    if (isset($result['error']))
		    $result_output = 'Error: '.$result['error']."\n";
	    else
            $result_output = 'TxID: '.$result['txid']."\nWait a few seconds then check on: http://coinsecrets.org/\n";
*/
    
    }

    if ($_GET['_mode']=='update_agent'){

        $Roles = array();
        $KeyValueEntries = array();

        $KeyValueEntry = new KeyValueEntry();
        $KeyValueEntry->setKey('email');
        $KeyValueEntry->setValue($_GET['_Name']);
        $KeyValueEntries[]=$KeyValueEntry;

        $UpdateAgentAction = new UpdateAgentAction();
        $UpdateAgentAction->setOrgId($_GET['_OrgId']);
        $UpdateAgentAction->setPublicKey($_GET['_PublicKey']);
        $UpdateAgentAction->setActive($_GET['_Active']);
        $UpdateAgentAction->setRoles($Roles);
        $UpdateAgentAction->setMetadata($KeyValueEntries);

        $send_data = $UpdateAgentAction->serializeToString();
        $send_address = 'DFcP5QFjbYtfgzWoqGedhxecCrRe41G3RD';
        $private_key = 'L44NzghbN6UD737kG6ukfdCq6BXyyTY2W15UkNhHnBff6acYWtsZ';
        $send_amount = 0.001;
    
        try {
            $agents = $AgentList->getAgents();
            $Agent->mergeFromString($send_data);
            foreach ( $agents as $agent ){

            }
            //$agents[] = $Agent;
            $AgentList->setAgents($agents);
            //$send_data = $AgentList->serializeToString();
        } catch (Exception $e) {
            // Handle parsing error from invalid data.
            // ...
        }
/*
	    $result = OP_RETURN_send($send_address, $send_amount, $send_data);
	
	    if (isset($result['error']))
		    $result_output = 'Error: '.$result['error']."\n";
	    else
            $result_output = 'TxID: '.$result['txid']."\nWait a few seconds then check on: http://coinsecrets.org/\n";
*/
        
    }


    $output  = '<form method="post">';
    $output .= '<figure class="wp-block-table"><table><tbody>';
    $output .= '<tr><td>LoginName</td><td>PublicKey</td><td></td><td></td></tr>';

    //$metadata = '';
    $agents = $AgentList->getAgents();
    foreach ($agents as $index => $agent) {
        $PublicKey = $agents[$index]->getPublicKey();
        $KeyValueEntries = $agents[$index]->getMetadata();
        foreach ($KeyValueEntries as $KeyValueEntry)
            if ($KeyValueEntry->getKey()=='email') 
                $LoginName = $KeyValueEntry->getValue();
        $output .= '<tr><td>'.$LoginName.'</td><td>'.$PublicKey.'</td>';
        //$output .= '<td><a href="?_mode=agent_edit&_PublicKey='.$PublicKey.'&_LoginName='.$LoginName.'&_submit=update">Update</a></td>';
        //$output .= '<td>'.'<a href="?_mode=agent_edit">Delete</a>'.'</td>';
        $output .= '<input type="hidden" value="'.$index.'" name="_item">';
        //$output .= '<input type="hidden" value="'.$LoginName.'" name="_LoginName_'.$index.'">';
        //$output .= '<input type="hidden" value="'.$PublicKey.'" name="_PublicKey_'.$index.'">';
        $output .= '<td><input class="wp-block-button__link" type="submit" value="Update" name="edit_agent"></td>';
        $output .= '<td><input class="wp-block-button__link" type="submit" value="Delete" name="edit_agent"></td>';
        $output .= '</tr>';
    }

    $output .= '</tbody></table></figure>';

    $output .= '<div class="wp-block-buttons">';
    $output .= '<div class="wp-block-button">';
    $output .= '<input class="wp-block-button__link" type="submit" value="Create New" name="edit_agent">';
    $output .= '</div>';
    //$output .= '<div class="wp-block-button">';
    //$output .= '<a class="wp-block-button__link" href="?_mode=edit_agent&_submit=create">Create New</a>';
    //$output .= '</div>';
    $output .= '<div class="wp-block-button">';
    $output .= '<a class="wp-block-button__link" href="/">Cancel</a>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</form>';

    return $output;    
}


add_shortcode( 'agent_edit', 'agent_callback' );
function agent_callback( $atts = [], $content = null, $tag = '' ) {

    //$PikePayloadAction = new PikePayload_Action();
    //$PikePayload = new PikePayload();
    $KeyValueEntry = new KeyValueEntry();
    $KeyValueEntry->setKey('email');
    $KeyValueEntry->setValue('rove.k.chen@gmail.com');

    $CreateAgentAction = new CreateAgentAction();
    $CreateAgentAction->setOrgId('001');
    $CreateAgentAction->setPublicKey('DFcP5QFjbYtfgzWoqGedhxecCrRe41G3RD');
    $CreateAgentAction->setActive(true);
    $CreateAgentAction->setRoles(['003','004']);
    $CreateAgentAction->setMetadata([$KeyValueEntry]);
    $send_data = $CreateAgentAction->serializeToString();

    $AgentList = new AgentList();
    $Agent = new Agent();
    try {
        $Agent->mergeFromString($send_data);
        $agents = $AgentList->getAgents();
        $agents[] = $Agent;
        $AgentList->setAgents($agents);
        $send_data = $AgentList->serializeToString();
    } catch (Exception $e) {
        // Handle parsing error from invalid data.
        // ...
    }
    $agents = $AgentList->getAgents();
  
    $send_address = 'DFcP5QFjbYtfgzWoqGedhxecCrRe41G3RD';
    $private_key = 'L44NzghbN6UD737kG6ukfdCq6BXyyTY2W15UkNhHnBff6acYWtsZ';
    $send_amount = 0.001;
/*
	$result = OP_RETURN_send($send_address, $send_amount, $send_data);
	
	if (isset($result['error']))
		$result_output = 'Error: '.$result['error']."\n";
	else
        $result_output = 'TxID: '.$result['txid']."\nWait a few seconds then check on: http://coinsecrets.org/\n";
*/
/*
    $data = $from->serializeToString();
    try {
      $to->mergeFromString($data);
    } catch (Exception $e) {
      // Handle parsing error from invalid data.
      // ...
    }
*/    
    $output = '<figure class="wp-block-table"><table><tbody>';
    foreach ($atts as $key => $value)
        $output .= '<tr><td>'.$key.'</td><td><input type="text" value="'.$value.'"></td></tr>';
/*
    foreach ($atts as $index => $att) {
        $KeyValueEntries = $atts[$index]->getMetadata();
        foreach ($KeyValueEntries as $i => $KeyValueEntry)
            if ($KeyValueEntry->getKey()=='email') 
                $metadata = $KeyValueEntry->getValue();
        $output .= '<tr><td>'.$metadata.'</td><td>'.$agents[$index]->getPublicKey().'</td></tr>';
    }
    $output .= '<tr><td>PublicKey</td><td><input type="text"></td></tr>';
    $output .= '<tr><td>Name</td><td><input type="text"></td></tr>';
*/    
/*
    $metadata = '';

    //$output .= '<tr><td> </td><td>'.$result_output.'</td></tr>';
    $output .= '<tr><td>send_data</td><td>'.$send_data.'</td></tr>';
*/
    $output .= '</tbody></table></figure>';

    $output .= '<div class="wp-block-buttons">';
    $output .= '<div class="wp-block-button">';
    $output .= '<a class="wp-block-button__link" href="/agents/">Ok</a>';
    $output .= '</div>';
    $output .= '<div class="wp-block-button">';
    $output .= '<a class="wp-block-button__link" href="/agents/">Cancel</a>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;    
}

add_shortcode( 'shortcode_name', 'shortcode_handler_function' );
function shortcode_handler_function( $atts = [], $content = null, $tag = '' ) {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
 
    // override default attributes with user attributes
    $wporg_atts = shortcode_atts(
        array(
            'title' => 'WordPress.org',
        ), $atts, $tag
    );
 
    // start box
    $o = '<div class="wporg-box">';
 
    // title
    //$o .= '<h2>' . esc_html__( $wporg_atts['title'], 'wporg' ) . '</h2>';
 
    // enclosing tags
    if ( ! is_null( $content ) ) {
        // secure output by executing the_content filter hook on $content
        //$o .= apply_filters( 'the_content', $content );
 
        // run shortcode parser recursively
        $o .= do_shortcode( $content );
    }
 
    // end box
    $o .= '</div>';
 
    // return output
    return $o;
}

add_shortcode( 'TodoItems', 'todo_items_callback' );
function todo_items_callback() {
    $args     = array(
        'method' => 'GET',
    );
    $response = wp_remote_request( 'https://localhost:5001/api/TodoItems', $args );
    $body     = wp_remote_retrieve_body( $response );
    $body = wp_remote_retrieve_body( wp_remote_get( 'https://localhost:5001/api/TodoItems' ) );
    $result   = json_decode( $body );
    return print_r($result);
    //return json_decode($response);

    if ( is_array( $result ) && ! is_wp_error( $result ) ) {
        // Work with the $result data
    } else {
        // Work with the error
    }
    $output = 'name:'.$body['name'];
    return $output;
}

/*
 * Define DG_PIG_PLUGIN_FILE.
 */
if ( ! defined( 'DG_PIG_PLUGIN_FILE' ) ) {
    define( 'DG_PIG_PLUGIN_FILE', __FILE__ );
}

/*
 * Include the main class.
 */
//if ( ! class_exists( 'dg_pig' ) ) {
    include_once dirname( __FILE__ ) . '/includes/class-dg-pig.php';
//}

/*
function dg_pig(){
    return dg_pig::instance();
}

$GLOBALS['dg-pig'] = dg_pig();
*/

?>
