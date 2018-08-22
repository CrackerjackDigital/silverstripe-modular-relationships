<?php
namespace Modular\Relationships;

use FormField;
use Modular\Traits\upload;
use Modular\Types\FileType;
use UploadField;

/**
 * File implementation in relationships module which uses HasOne (there is another in modular-fields which doesn't)
 *
 * @package Modular\Relationships
 */
class File extends HasOne implements FileType {
	use upload;

	const Name                    = 'File';
	const DefaultUploadFolderName = 'files';

	// if an array then file extensions, if a string then a category e.g. 'video'

	private static $allowed_files = 'download';

	private static $tab_name = 'Root.Files';

	// folder directly under '/assets'
	private static $base_upload_folder = '';

	// this will be appended to 'base_upload_folder'
	private static $upload_folder = self::DefaultUploadFolderName;

	public function cmsField( $mode = null ) {
		return [
			$this->makeUploadField( static::field_name() ),
		];
	}

	public static function allowed_files() {
		return 'allowed_files';
	}

	public function customFieldConstraints( FormField $field, array $allFieldConstraints ) {
		$fieldName = $field->getName();
		/** @var UploadField $field */
		if ( $fieldName == static::field_name() ) {
			$this->configureUploadField( $field, static::allowed_files() );
		}
	}

	/**
	 * If file is versioned we need to publish it also.
	 */
	public function onAfterPublish() {
		/** @var \File|\Versioned $file */
		if ( $file = $this()->{static::relationship_name()}() ) {
			if ( $file->hasExtension( 'Versioned' ) ) {
				$file->publish( 'Stage', 'Live', false );
			}
		}
	}

}