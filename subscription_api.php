<?php
/**
 * @author Prefix Technologies 
 * @copyright (C) 2012-Prefix Technologies
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// Ascending or decending order for returned results
define ('ORDER_ASC', "ASC");
define ('ORDER_DESC', "DESC");

// Status values on returned results
define ('STATUS_SUCCESS', "success");
define ('STATUS_WARNING', "warning");
define ('STATUS_ERROR', "error");

// Constants relating to contacts
define ('CONTACT_SUBSCRIPTION_STATUS_SUBSCRIBED', "subscribed");
define ('CONTACT_SUBSCRIPTION_STATUS_UNSUBSCRIBED', "unsubscribed");
define ('CONTACT_SUBSCRIPTION_STATUS_UNCONFIRMED', "unconfirmed");
define ('CONTACT_SUBSCRIPTION_STATUS_FORWARDED', "forwarded");

define ('CONTACT_STATUS_ON', "on");
define ('CONTACT_STATUS_OFF', "off");
define ('CONTACT_STATUS_SUPPRESSED', "suppressed");

define ('CONTACT_EMAIL_SMS_STATUS_NONE', "none");
define ('CONTACT_EMAIL_SMS_STATUS_BOUNCING', "bouncing");
define ('CONTACT_EMAIL_SMS_STATUS_BOUNCED', "bounced");
define ('CONTACT_EMAIL_SMS_STATUS_ALWAYS_SEND', "always send");

define ('CONTACT_ACTION_ADD', "add");
define ('CONTACT_ACTION_UPDATE', "update");
define ('CONTACT_ACTION_IGNORE', "ignore");

define ('CONTACT_GENDER_UNKNOWN', "unknown");
define ('CONTACT_GENDER_MALE', "male");
define ('CONTACT_GENDER_FEMALE', "female");

define ('CONTACT_MARITAL_STATUS_UNKNOWN', "unknown");
define ('CONTACT_MARITAL_STATUS_SINGLE', "single");
define ('CONTACT_MARITAL_STATUS_MARRIED', "married");

define ('CONTACT_PREFERED_EMAIL_FORMAT_HTML', "html");
define ('CONTACT_PREFERED_EMAIL_FORMAT_TEXT', "text");

// Constants relating to messages
define ('MESSAGE_TYPE_EMAIL', "email");
define ('MESSAGE_TYPE_SMS', "sms");

define ('MESSAGE_HTML_TEXT_FETCH_WHEN_NOW', "now");
define ('MESSAGE_HTML_TEXT_FETCH_WHEN_CONFIRM', "confirm");
define ('MESSAGE_HTML_TEXT_FETCH_WHEN_SEND', "send");

define ('MESSAGE_SEND_SCHEDULE_RECURRENCE_ONCE', "once");
define ('MESSAGE_SEND_SCHEDULE_RECURRENCE_WEEKDAYS', "weekdays");
define ('MESSAGE_SEND_SCHEDULE_RECURRENCE_DAILY', "daily");
define ('MESSAGE_SEND_SCHEDULE_RECURRENCE_WEEKLY', "weekly");
define ('MESSAGE_SEND_SCHEDULE_RECURRENCE_MONTHLY', "monthly");

define ('MESSAGE_PRIORITY_LOW', "low");
define ('MESSAGE_PRIORITY_MEDIUM', "medium");
define ('MESSAGE_PRIORITY_HIGH', "high");

define ('MESSAGE_ENCODING_7BIT', "7bit");
define ('MESSAGE_ENCODING_8BIT', "8bit");
define ('MESSAGE_ENCODING_BINARY', "binary");
define ('MESSAGE_ENCODING_QUOTED_PRINTABLE', "quoted-printable");
define ('MESSAGE_ENCODING_BASE64', "base64");

/**
 * Mailer subscription API. Methods for lists, contacts, messages and templates.
 *
 * Sample:
 * $api = new MailerSubscriptionApiV1_0('qa.pmailer.net', 'jkhjkh');
 * $lists = $api->getLists(); // returns an array of lists
 *
 * @version 1.0
 * @author Prefix Technologies
 *
 */
class MailerSubscriptionApiV1_0
{
    var $_url = null;
    var $_key = null;
    /**
     * RPC Client object
     * @var IXR_Client
     */
    var $_xml_rpc = null;

    /**
     * Constructs a new api interaction object.
     * @param string $url API URL eg live.everlytic.com
     * @param string $api_key API key
     */
    function MailerSubscriptionApiV1_0($url, $api_key)
    {
        $this->_url = $url;
        $this->_key = $api_key;
        $this->_xml_rpc = new IXR_Client('http://'.$this->_url.'/api/1.0/');

    }


    /**
     * Retrieves api response for getting lists in an array.
     *
     * @param array $filter Eg array('list_name' => 'test'); .
     * @param array $order Column to order by ASC or DESC
     * @param $start Start page param.
     * @param $limit Limit how many results to return.
     *
     * @throws PMailerSubscriptionException
     *
     * @return array
     */
    function getLists($filter = array(), $order = array('list_name' => 'ASC'),
        $start = 1, $limit = 50)
    {
        $success =
            $this->_xml_rpc->query(
                'lists.getBatch',
                $this->_key,
                $filter,
                $order,
                $start,
                $limit
            );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Retrieves api response for getting a list in an array.
     *
     * @param integer $listId Identifier of list to be got.
     *
     * @throws PMailerSubscriptionException
     *
     * @return array
     */
    function getListById($listId)
    {
        $success =
            $this->_xml_rpc->query(
                'lists.get',
                $this->_key,
                $listId
            );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Retrieves api response for getting contacts on a list in an array.
     *
     * @param integer $listId Identifier of the list.
     * @param array $filter Eg array('list_name' => 'test'); .
     * @param array $order Column to order by ASC or DESC
     *
     * @throws PMailerSubscriptionException
     *
     * @return array
     */
    function getContactsOnList($listId, $filter = array(),
        $order = array('contact_name' => 'ASC'))
    {
        $success =
            $this->_xml_rpc->query(
                'lists.getContacts',
                $this->_key,
                $listId,
                $filter,
                $order
            );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Creates new contact list.
     *
     * @param array $filter Eg array('list_name' => 'test'); .
     *
     * @throws PMailerSubscriptionException
     *
     * @return array
     */
    function createList($list = array())
    {
        $success =
            $this->_xml_rpc->query(
                'lists.create',
                $this->_key,
                $list
            );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Updates existing contact list.
     *
     * @param integer $listId Identifier of list to be updated.
     * @param array $list Array of list properties to be updated.
     *
     * @throws PMailerSubscriptionException
     *
     * @return array
     */
    function updateList($listId, $list = array())
    {
        $success =
            $this->_xml_rpc->query(
                'lists.update',
                $this->_key,
                $listId,
                $list
            );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Deletes contact list by ID.
     *
     * @param integer $listId Identifier of list to be deleted.
     *
     * @throws PMailerSubscriptionException
     *
     * @return array
     */
    function deleteList($listId)
    {
        $success =
            $this->_xml_rpc->query(
                'lists.delete',
                $this->_key,
                $listId
            );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Empties the list of all contacts.
     *
     * @param integer $listId Identifier of list to be emptied.
     *
     * @throws PMailerSubscriptionException
     *
     * @return array
     */
    function emptyList($listId)
    {
        $success =
            $this->_xml_rpc->query(
                'lists.emptyList',
                $this->_key,
                $listId
            );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Combines two lists.
     *
     * @param integer $authListId Identifier of authoritive list.
     * @param integer $nonAuthListId Identifier of non-authoritive list.
     *
     * @throws PMailerSubscriptionException
     *
     * @return array
     */
    function combineLists($authListId, $nonAuthListId)
    {
        $lists = array(
            'auth_list' => $authListId,
            'non_auth_list' => $nonAuthListId
        );
        $success =
            $this->_xml_rpc->query(
                'lists.combine',
                $this->_key,
                $lists
            );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

/**
     * Gets a batch of contacts.
     *
     * @param struct|array $filter Filters to apply to the query.
     * @param struct|array $order Order to apply to the query.
     * @param integer $page Pagination page.
     * @param integer $limit Items per page.
     *
     * @return array
     */
    public function getContacts($filter = array(), $order = array(),
        $page = 1, $limit = 50)
    {
        $success =
            $this->_xml_rpc->query(
                'contacts.getBatch',
                $this->_key,
                $filter,
                $order,
                $page,
                $limit
            );

        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Gets a single contact.
     *
     * @param integer $contactId Contacts identifier.
     *
     * @return array
     */
    public function getContactById($contactId)
    {
        $success =
            $this->_xml_rpc->query(
                'contacts.get',
                $this->_key,
                $contactId
            );

        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Gets lists contact is on.
     *
     * @param integer $contactId Contacts identifier.
     * @param struct|array $filter Filters to apply to the query.
     * @param struct|array $order Order to apply to the query.
     * @param integer $page Pagination page.
     * @param integer $limit Items per page.
     *
     * @return array
     */
    public function getContactSubscriptions($contactId, $filter = array(),
        $order = array(), $page = 1, $limit = 50)
    {
        $success =
            $this->_xml_rpc->query(
                'contacts.getLists',
                $this->_key,
                $contactId,
                $filter,
                $order,
                $page,
                $limit
            );

        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Creates a new contact.
     *
     * @param array $contact Contacts parameters.
     * @param array $listIds Lists to add contact to.
     * @param string $status Contact status on list.
     * @param string $action Action to perform when duplicates found.
     *
     * @return array
     */
    public function createContact(array $contact, array $listIds,
        $status = 'subscribed', $action = 'add')
    {
        $success = $this->_xml_rpc->query(
            'contacts.create',
            $this->_key,
            $contact,
            $listIds,
            $status,
            $action
        );

        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Creates a batch of new contacts.
     *
     * @param array $contacts Array of contacts containing parameters.
     * @param array $listIds Lists to add contacts to.
     * @param string $status Contact status on list.
     * @param string $action Action to perform when duplicates found.
     *
     * @return array
     */
    public function createContactBatch(array $contacts, array $listIds,
        $status = 'subscribed', $action = 'add')
    {
        $success =
            $this->_xml_rpc->query(
                'contacts.createBatch',
                $this->_key,
                $contacts,
                $listIds,
                $status,
                $action
            );

        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Updates a contact.
     *
     * @param integer $contactId Contact identifier.
     * @param array $contact Contacts parameters.
     *
     * @return array
     */
    public function updateContact($contactId, array $contact)
    {
        $success =
            $this->_xml_rpc->query(
                'contacts.update',
                $this->_key,
                $contactId,
                $contact
            );

        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Updates a batch of contacts.
     *
     * @param array $contacts Array of contacts containing parameters.
     *
     * @return array
     */
    public function updateContactBatch(array $contacts)
    {
        $success =
            $this->_xml_rpc->query(
                'contacts.updateBatch',
                $this->_key,
                $contacts
            );

        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Retrieves api response for updating a single contact's subscriptions in an array.
     *
     * @param int $contact_id Contact ID.
     * @param array $contact_lists Contact ID.
     *
     * @throws PMailerSubscriptionException
     *
     * @return array
     */
    function updateContactSubscriptions($contact_id, $contact_lists)
    {
        $success =
            $this->_xml_rpc->query(
                'contacts.updateSubscriptions',
                $this->_key,
                $contact_id,
                $contact_lists
            );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Deletes a contact.
     *
     * @param integer $contactId Contact identifier.
     *
     * @return array
     */
    public function deleteContact($contactId)
    {
        $success =
            $this->_xml_rpc->query(
                'contacts.delete',
                $this->_key,
                $contactId
            );

        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();
    }

    /**
     * Deletes a batch of contacts.
     *
     * @param array $contactIds Array of contact identifiers.
     *
     * @return array
     */
    public function deleteContactBatch(array $contactIds)
    {
        $identifiers = array();
        foreach ( $contactIds as $id )
        {
            $identifiers[] = array('identifier' => $id);
        }
        $success =
            $this->_xml_rpc->query(
                'contacts.deleteBatch',
                $this->_key,
                $identifiers
            );

        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Gets a batch of messages.
    *
    * @param struct|array $filter Filters to apply to the query.
    * @param struct|array $order Order to apply to the query.
    * @param integer $page Pagination page.
    * @param integer $limit Items per page.
    *
    * @return array
    */
    public function getMessages($filter = array(), $order = array(),
        $page = 1, $limit = 50)
    {
        $success = $this->_xml_rpc->query(
            'messages.getBatch',
            $this->_key,
            $filter,
            $order,
            $page,
            $limit
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Gets a single message.
    *
    * @param integer $messageId Message identifier.
    *
    * @return array
    */
    public function getMessageById($messageId)
    {
        $success = $this->_xml_rpc->query(
            'messages.get',
            $this->_key,
            $messageId
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Creates a new message.
    *
    * @param string $messageType Type of message to create.
    * @param array|null $using Message or template to base new message on.
    * @param array $message Message containing parameters.
    *
    * @return array
    */
    public function createMessage($messageType, array $using, array $message)
    {
        $success = $this->_xml_rpc->query(
            'messages.create',
            $this->_key,
            $messageType,
            $using,
            $message
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Updates a message.
    *
    * @param integer $messageId Message identifier.
    * @param array $message Message parameters.
    *
    * @return array
    */
    public function updateMessage($messageId, array $message)
    {
        $success = $this->_xml_rpc->query(
            'messages.update',
            $this->_key,
            $messageId,
            $message
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Deletes a message.
    *
    * @param integer $messageId Message identifier.
    *
    * @return array
    */
    public function deleteMessage($messageId)
    {
        $success = $this->_xml_rpc->query(
            'messages.delete',
            $this->_key,
            $messageId
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Sends a message.
    *
    * @param integer $messageId Message identifier.
    *
    * @return array
    */
    public function sendMessage($messageId)
    {
        $success = $this->_xml_rpc->query(
            'messages.send',
            $this->_key,
            $messageId
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Previews a message.
    *
    * @param integer $messageId Message identifier.
    * @param string $recipient Message preview recipient.
    *
    * @return array
    */
    public function previewMesasge($messageId, $recipient)
    {
        $success = $this->_xml_rpc->query(
            'messages.preview',
            $this->_key,
            $messageId,
            $recipient
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Pauses a message.
    *
    * @param integer $messageId Message identifier.
    *
    * @return array
    */
    public function pauseMessage($messageId)
    {
        $success = $this->_xml_rpc->query(
            'messages.pause',
            $this->_key,
            $messageId
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Resumes a message.
    *
    * @param integer $messageId Message identifier.
    *
    * @return array
    */
    public function resumeMessage($messageId)
    {
        $success = $this->_xml_rpc->query(
            'messages.resume',
            $this->_key,
            $messageId
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Cancels a message.
    *
    * @param integer $messageId Message identifier.
    *
    * @return array
    */
    public function cancelMessage ($messageId)
    {
        $success = $this->_xml_rpc->query(
            'messages.cancel',
            $this->_key,
            $messageId
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Gets a batch of templates.
    *
    * @param struct|array $filter Filters to apply to the query.
    * @param struct|array $order Order to apply to the query.
    * @param integer $page Pagination page.
    * @param integer $limit Items per page.
    *
    * @return array
    */
    public function getTemplates($filter = array(), $order = array(),
        $page = 1, $limit = 50)
    {
        $success = $this->_xml_rpc->query(
            'templates.getBatch',
            $this->_key,
            $filter,
            $order,
            $page,
            $limit
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

    /**
    * Gets a single template.
    *
    * @param integer $templateId Template identifier.
    *
    * @return array
    */
    public function getTemplateById($templateId)
    {
        $success = $this->_xml_rpc->query(
            'templates.get',
            $this->_key,
            $templateId
        );
        if ( $success !== true )
        {
            throw new PMailerSubscriptionException(
                $this->_xml_rpc->getErrorMessage()
            );
        }

        return $this->_xml_rpc->getResponse();

    }

}

/**
 * Exception that is thrown when API interaction occurs.
 * @author Prefix
 *
 */
class PMailerSubscriptionException extends Exception
{
    /**
     * Constructs a new PMailerSubscriptionException.
     *
     */
    public function __construct($message)
    {
        parent::__construct($message);

    }
}

/**
 * IXR_Client
 *
 * @package IXR
 * @since 1.5
 *
 */
class IXR_Client
{
    var $server;
    var $port;
    var $path;
    var $useragent;
    var $response;
    var $message = false;
    var $debug = false;
    var $timeout;
    var $headers = array();

    // Storage place for an error message
    var $error = false;

    function IXR_Client($server, $path = false, $port = 80, $timeout = 15)
    {
        if (!$path) {
            // Assume we have been given a URL instead
            $bits = parse_url($server);
            $this->server = $bits['host'];
            $this->port = isset($bits['port']) ? $bits['port'] : 80;
            $this->path = isset($bits['path']) ? $bits['path'] : '/';

            // Make absolutely sure we have a path
            if (!$this->path) {
                $this->path = '/';
            }
        } else {
            $this->server = $server;
            $this->path = $path;
            $this->port = $port;
        }
        $this->useragent = 'pMailer Plugin';
        $this->timeout = $timeout;
    }

    function query()
    {
        $args = func_get_args();
        $method = array_shift($args);
        $request = new IXR_Request($method, $args);
        $length = $request->getLength();
        $xml = $request->getXml();
        $r = "\r\n";
        $request  = 'POST ' . $this->path . ' HTTP/1.0' . $r;

        // Merged from WP #8145 - allow custom headers
        $this->headers['Host']          = $this->server;
        $this->headers['Content-Type']  = 'text/xml';
        $this->headers['User-Agent']    = $this->useragent;
        $this->headers['Content-Length']= $length;

        foreach( $this->headers as $header => $value ) {
            $request .= $header . ': ' . $value . $r ;
        }
        $request .= $r;

        $request .= $xml;

        // Now send the request
        if ($this->debug) {
            echo '<pre class="ixr_request">'.htmlspecialchars($request)."\n".'</pre>'."\n\n";
        }

        if ($this->timeout) {
            $fp = @fsockopen($this->server, $this->port, $errno, $errstr, $this->timeout);
        } else {
            $fp = @fsockopen($this->server, $this->port, $errno, $errstr);
        }
        if (!$fp) {
            $this->error = new IXR_Error(-32300, 'transport error - could not open socket');
            return false;
        }
        fputs($fp, $request);
        $contents = '';
        $debugContents = '';
        $gotFirstLine = false;
        $gettingHeaders = true;
        while (!feof($fp)) {
            $line = fgets($fp, 4096);
            if (!$gotFirstLine) {
                // Check line for '200'
                if (strstr($line, '200') === false) {
                    $this->error = new IXR_Error(-32300, 'transport error - HTTP status code was not 200');
                    return false;
                }
                $gotFirstLine = true;
            }
            if (trim($line) == '') {
                $gettingHeaders = false;
            }
            if (!$gettingHeaders) {
                // merged from WP #12559 - remove trim
                $contents .= $line;
            }
            if ($this->debug) {
                $debugContents .= $line;
            }
        }
        if ($this->debug) {
            echo '<pre class="ixr_response">'.htmlspecialchars($debugContents)."\n".'</pre>'."\n\n";
        }

        // Now parse what we've got back
        $this->message = new IXR_Message($contents);
        if (!$this->message->parse()) {
            // XML error

            $this->error = new IXR_Error(-32700, 'parse error. not well formed');
            return false;
        }

        // Is the message a fault?
        if ($this->message->messageType == 'fault') {
            $this->error = new IXR_Error($this->message->faultCode, $this->message->faultString);
            return false;
        }

        // Message must be OK
        return true;
    }

    function getResponse()
    {
        // methodResponses can only have one param - return that
        return $this->message->params[0];
    }

    function isError()
    {
        return (is_object($this->error));
    }

    function getErrorCode()
    {
        return $this->error->code;
    }

    function getErrorMessage()
    {
        return $this->error->message;
    }
}

/**
 * IXR_MESSAGE
 *
 * @package IXR
 * @since 1.5
 *
 */
class IXR_Message
{
    var $message;
    var $messageType;  // methodCall / methodResponse / fault
    var $faultCode;
    var $faultString;
    var $methodName;
    var $params;

    // Current variable stacks
    var $_arraystructs = array();   // The stack used to keep track of the current array/struct
    var $_arraystructstypes = array(); // Stack keeping track of if things are structs or array
    var $_currentStructName = array();  // A stack as well
    var $_param;
    var $_value;
    var $_currentTag;
    var $_currentTagContents;
    // The XML parser
    var $_parser;

    function IXR_Message($message)
    {
        $this->message =& $message;
    }

    function parse()
    {
        // first remove the XML declaration
        // merged from WP #10698 - this method avoids the RAM usage of preg_replace on very large messages
        $header = preg_replace( '/<\?xml.*?\?'.'>/', '', substr($this->message, 0, 100), 1);
        $this->message = substr_replace($this->message, $header, 0, 100);
        if (trim($this->message) == '') {
            return false;
        }
        $this->_parser = xml_parser_create('UTF-8');
        // Set XML parser to take the case of tags in to account
        xml_parser_set_option($this->_parser, XML_OPTION_CASE_FOLDING, false);
        // Set XML parser callback functions
        xml_set_object($this->_parser, $this);
        xml_set_element_handler($this->_parser, 'tag_open', 'tag_close');
        xml_set_character_data_handler($this->_parser, 'cdata');
        $chunk_size = 262144; // 256Kb, parse in chunks to avoid the RAM usage on very large messages
        do {
            if (strlen($this->message) <= $chunk_size) {
                $final = true;
            }
            $part = substr($this->message, 0, $chunk_size);
            $this->message = substr($this->message, $chunk_size);
            if (!xml_parse($this->_parser, $part, $final)) {
                $error = xml_error_string(xml_get_error_code($this->_parser));
                var_dump(
                    $error,
                    xml_get_current_line_number($this->_parser),
                    xml_get_current_column_number($this->_parser)
                );
                die($error);
                return false;
            }
            if ($final) {
                break;
            }
        } while (true);
        xml_parser_free($this->_parser);

        // Grab the error messages, if any
        if ($this->messageType == 'fault') {
            $this->faultCode = $this->params[0]['faultCode'];
            $this->faultString = $this->params[0]['faultString'];
        }
        return true;
    }

    function tag_open($parser, $tag, $attr)
    {
        $this->_currentTagContents = '';
        $this->currentTag = $tag;
        switch($tag) {
            case 'methodCall':
            case 'methodResponse':
            case 'fault':
                $this->messageType = $tag;
                break;
                /* Deal with stacks of arrays and structs */
            case 'data':    // data is to all intents and puposes more interesting than array
                $this->_arraystructstypes[] = 'array';
                $this->_arraystructs[] = array();
                break;
            case 'struct':
                $this->_arraystructstypes[] = 'struct';
                $this->_arraystructs[] = array();
                break;
        }
    }

    function cdata($parser, $cdata)
    {
        $this->_currentTagContents .= $cdata;
    }

    function tag_close($parser, $tag)
    {
        $valueFlag = false;
        switch($tag) {
            case 'int':
            case 'i4':
                $value = (int)trim($this->_currentTagContents);
                $valueFlag = true;
                break;
            case 'double':
                $value = (double)trim($this->_currentTagContents);
                $valueFlag = true;
                break;
            case 'string':
                $value = (string)trim($this->_currentTagContents);
                $valueFlag = true;
                break;
            case 'dateTime.iso8601':
                $value = new IXR_Date(trim($this->_currentTagContents));
                $valueFlag = true;
                break;
            case 'value':
                // 'If no type is indicated, the type is string.'
                if (trim($this->_currentTagContents) != '') {
                    $value = (string)$this->_currentTagContents;
                    $valueFlag = true;
                }
                break;
            case 'boolean':
                $value = (boolean)trim($this->_currentTagContents);
                $valueFlag = true;
                break;
            case 'base64':
                $value = base64_decode($this->_currentTagContents);
                $valueFlag = true;
                break;
                /* Deal with stacks of arrays and structs */
            case 'data':
            case 'struct':
                $value = array_pop($this->_arraystructs);
                array_pop($this->_arraystructstypes);
                $valueFlag = true;
                break;
            case 'member':
                array_pop($this->_currentStructName);
                break;
            case 'name':
                $this->_currentStructName[] = trim($this->_currentTagContents);
                break;
            case 'methodName':
                $this->methodName = trim($this->_currentTagContents);
                break;
        }

        if ($valueFlag) {
            if (count($this->_arraystructs) > 0) {
                // Add value to struct or array
                if ($this->_arraystructstypes[count($this->_arraystructstypes)-1] == 'struct') {
                    // Add to struct
                    $this->_arraystructs[count($this->_arraystructs)-1][$this->_currentStructName[count($this->_currentStructName)-1]] = $value;
                } else {
                    // Add to array
                    $this->_arraystructs[count($this->_arraystructs)-1][] = $value;
                }
            } else {
                // Just add as a paramater
                $this->params[] = $value;
            }
        }
        $this->_currentTagContents = '';
    }
}

/**
 * IXR_Request
 *
 * @package IXR
 * @since 1.5
 */
class IXR_Request
{
    var $method;
    var $args;
    var $xml;

    function IXR_Request($method, $args)
    {
        $this->method = $method;
        $this->args = $args;
        $this->xml = <<<EOD
<?xml version='1.0'?>
<methodCall>
<methodName>{$this->method}</methodName>
<params>

EOD;
        foreach ($this->args as $arg) {
            $this->xml .= '<param><value>';
            $v = new IXR_Value($arg);
            $this->xml .= $v->getXml();
            $this->xml .= '</value></param>'."\n";
        }
        $this->xml .= '</params>'."\n".'</methodCall>';
    }

    function getLength()
    {
        return strlen($this->xml);
    }

    function getXml()
    {
        return $this->xml;
    }
}

/**
 * IXR_Date
 *
 * @package IXR
 * @since 1.5
 */
class IXR_Date {
    var $year;
    var $month;
    var $day;
    var $hour;
    var $minute;
    var $second;
    var $timezone;

    function IXR_Date($time)
    {
        // $time can be a PHP timestamp or an ISO one
        if (is_numeric($time)) {
            $this->parseTimestamp($time);
        } else {
            $this->parseIso($time);
        }
    }

    function parseTimestamp($timestamp)
    {
        $this->year = date('Y', $timestamp);
        $this->month = date('m', $timestamp);
        $this->day = date('d', $timestamp);
        $this->hour = date('H', $timestamp);
        $this->minute = date('i', $timestamp);
        $this->second = date('s', $timestamp);
        $this->timezone = '';
    }

    function parseIso($iso)
    {
        $this->year = substr($iso, 0, 4);
        $this->month = substr($iso, 4, 2);
        $this->day = substr($iso, 6, 2);
        $this->hour = substr($iso, 9, 2);
        $this->minute = substr($iso, 12, 2);
        $this->second = substr($iso, 15, 2);
        $this->timezone = substr($iso, 17);
    }

    function getIso()
    {
        return $this->year.$this->month.$this->day.'T'.$this->hour.':'.$this->minute.':'.$this->second.$this->timezone;
    }

    function getXml()
    {
        return '<dateTime.iso8601>'.$this->getIso().'</dateTime.iso8601>';
    }

    function getTimestamp()
    {
        return mktime($this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year);
    }
}

class IXR_Value
{
    var $data;
    var $type;

    function IXR_Value($data, $type = false)
    {
        $this->data = $data;
        if (!$type) {
            $type = $this->calculateType();
        }
        $this->type = $type;
        if ($type == 'struct') {
            // Turn all the values in the array in to new IXR_Value objects
            foreach ($this->data as $key => $value) {
                $this->data[$key] = new IXR_Value($value);
            }
        }
        if ($type == 'array') {
            for ($i = 0, $j = count($this->data); $i < $j; $i++) {
                $this->data[$i] = new IXR_Value($this->data[$i]);
            }
        }
    }

    function calculateType()
    {
        if ($this->data === true || $this->data === false) {
            return 'boolean';
        }
        if (is_integer($this->data)) {
            return 'int';
        }
        if (is_double($this->data)) {
            return 'double';
        }

        // Deal with IXR object types base64 and date
        if (is_object($this->data) && is_a($this->data, 'IXR_Date')) {
            return 'date';
        }
        if (is_object($this->data) && is_a($this->data, 'IXR_Base64')) {
            return 'base64';
        }

        // If it is a normal PHP object convert it in to a struct
        if (is_object($this->data)) {
            $this->data = get_object_vars($this->data);
            return 'struct';
        }
        if (!is_array($this->data)) {
            return 'string';
        }

        // We have an array - is it an array or a struct?
        if ($this->isStruct($this->data)) {
            return 'struct';
        } else {
            return 'array';
        }
    }

    function getXml()
    {
        // Return XML for this value
        switch ($this->type) {
            case 'boolean':
                return '<boolean>'.(($this->data) ? '1' : '0').'</boolean>';
                break;
            case 'int':
                return '<int>'.$this->data.'</int>';
                break;
            case 'double':
                return '<double>'.$this->data.'</double>';
                break;
            case 'string':
                return '<string>'.htmlspecialchars($this->data).'</string>';
                break;
            case 'array':
                $return = '<array><data>'."\n";
                foreach ($this->data as $item) {
                    $return .= '  <value>'.$item->getXml().'</value>'."\n";
                }
                $return .= '</data></array>';
                return $return;
                break;
            case 'struct':
                $return = '<struct>'."\n";
                foreach ($this->data as $name => $value) {
                    $return .= '  <member><name>' .$name . '</name><value>';
                    $return .= $value->getXml().'</value></member>'."\n";
                }
                $return .= '</struct>';
                return $return;
                break;
            case 'date':
            case 'base64':
                return $this->data->getXml();
                break;
        }
        return false;
    }

    /**
     * Checks whether or not the supplied array is a struct or not
     *
     * @param unknown_type $array
     * @return boolean
     */
    function isStruct($array)
    {
        $expected = 0;
        foreach ($array as $key => $value) {
            if ((string)$key != (string)$expected) {
                return true;
            }
            $expected++;
        }
        return false;
    }
}

/**
 * IXR_Error
 *
 * @package IXR
 * @since 1.5
 */
class IXR_Error
{
    var $code;
    var $message;

    function IXR_Error($code, $message)
    {
        $this->code = $code;
        $this->message = htmlspecialchars($message);
    }

    function getXml()
    {
        $xml = <<<EOD
<methodResponse>
  <fault>
    <value>
      <struct>
        <member>
          <name>faultCode</name>
          <value><int>{$this->code}</int></value>
        </member>
        <member>
          <name>faultString</name>
          <value><string>{$this->message}</string></value>
        </member>
      </struct>
    </value>
  </fault>
</methodResponse>

EOD;
        return $xml;
    }
}


?>