<?php

namespace Skybet\File;

interface FileInterface {

	public function Load();

	public function Save($fistname,$surname);

	public function Delete($id);

	public function Update($fistname,$surname,$id);
}