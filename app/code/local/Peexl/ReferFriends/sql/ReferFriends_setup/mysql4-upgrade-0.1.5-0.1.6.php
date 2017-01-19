<?php

$installer = $this;
$connection = $installer->getConnection();
$installer->startSetup();

$connection->insert($installer->getTable('core/email_template'), array(
    'template_code' => 'Referal Invitation Email Template',
    'template_text' => '{{var customer_text}}<br>{{var referal_url_link}}',
    'template_styles' => '',
    'template_subject' => 'Referral Registration Invitation',
    'template_sender_name' => null,
    'template_sender_email' => null,
    'template_type' => 2,
    'added_at'=>now(),
    'modified_at'=>now(),
    'orig_template_code' => 'peexl_referfriends_invitation_email_template',
    'orig_template_variables' => ''
));

$installer->endSetup();