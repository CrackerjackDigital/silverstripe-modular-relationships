<?php
namespace Modular\Relationships;

use Modular\Traits\upload;
use Modular\Types\FileType;
use SS_List;

/**
 * @method SS_List Links
 */
class HasFiles extends HasManyMany implements FileType {
	use upload;

	const Name = 'Files';

	private static $allowed_files = 'download';

	public function cmsFields($mode = null) {
		return [
			new \UploadField(
				static::field_name()
			)
		];
	}

	public function customFieldConstraints(\FormField $field, array $allFieldConstraints) {
		if ($field->getName() == static::field_name()) {
			$this->configureUploadField($field);
		}
	}

}