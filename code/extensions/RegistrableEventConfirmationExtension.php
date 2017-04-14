<?php

class RegistrableEventConfirmationExtension extends DataExtension
{

	private static $db = array(
		'RegEmailConfirm' => 'Boolean',
		'EmailConfirmMessage' => 'Varchar(255)',
		'ConfirmTimeLimit' => 'Int',
		'AfterConfirmTitle' => 'Varchar(255)',
		'AfterConfirmContent' => 'HTMLText'
	);

	public static $defaults = array(
		'EmailConfirmMessage' => 'Important: You must check your emails and confirm your registration before it is valid.',
		'ConfirmTimeLimit' => 21600,
		'AfterConfirmTitle' => 'Registration Confirmed',
		'AfterConfirmContent' => '<p>Thanks! Your registration has been confirmed</p>'
	);

	public function updateCMSFields(FieldList $fields) {

		if ($this->owner->RegEmailConfirm) {
			$fields->addFieldToTab('Root.Main', new ToggleCompositeField(
				'AfterRegistrationConfirmation',
				_t('EventRegistration.AFTER_REG_CONFIRM_CONTENT', 'After Registration Confirmation Content'),
				array(
					new TextField('AfterConfirmTitle', _t('EventRegistration.TITLE', 'Title')),
					new HtmlEditorField('AfterConfirmContent', _t('EventRegistration.CONTENT', 'Content'))
				)
			));
		}

		if ($this->owner->RegEmailConfirm) {
			$unconfirmedGrid = new GridField('UnconfirmedRegistrations',
				_t('Registripe.UNCONFIRMED', 'Unconfirmed'),
				$this->owner->getUnconfirmedRegistrations()
					->sort("LastEdited", "DESC")
			);
			$tabset->push(new Tab("Unconfirmed", $unconfirmedGrid));
		}

	}

	public function updateSettingsCMSFields(FieldList $fields) {
		$fields->addFieldsToTab('Root.Email', array(
			new CheckboxField(
				'RegEmailConfirm',
				_t('Registripe.REQ_EMAIL_CONFIRM', 'Require email confirmation to complete free registrations?')
			),
			$info = new TextField(
				'EmailConfirmMessage',
				_t('Registripe.EMAIL_CONFIRM_INFO', 'Email confirmation information')
			),
			$limit = new NumericField(
				'ConfirmTimeLimit',
				_t('Registripe.EMAIL_CONFIRM_TIME_LIMIT', 'Email confirmation time limit')
			),
			new CheckboxField(
				'UnRegEmailConfirm',
				_t('Registripe.REQ_UN_REG_EMAIL_CONFIRM', 'Require email confirmation to un-register?')
			)
		));
	}

}
