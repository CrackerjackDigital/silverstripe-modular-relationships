<?php
namespace Modular\Relationships;
use Modular\Types\RefType;

/**
 * Add a single related page field.
 *
 * @package Modular\Relationships
 */
class HasRelatedPage extends HasOne implements RefType {
	const Name = 'Page';
	const Schema = 'Page';
}