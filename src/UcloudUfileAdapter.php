<?php
namespace Xujif\UcloudUfileStorage;

use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Adapter\Polyfill\NotSupportingVisibilityTrait;
use League\Flysystem\Adapter\Polyfill\StreamedReadingTrait;
use League\Flysystem\Adapter\Polyfill\StreamedWritingTrait;
use League\Flysystem\Config;
use Xujif\UcloudUfileSdk\UfileSdk;
class UcloudUfileAdapter extends AbstractAdapter {

	use NotSupportingVisibilityTrait, StreamedWritingTrait, StreamedReadingTrait;
	protected $ufileSdk;




	public function __construct($bucket, $public_key, $secret_key, $suffix = '.ufile.ucloud.cn',$pathPrefix='', $https = false) {
		$this->ufileSdk = new UfileSdk($bucket,$public_key,$secret_key,$suffix,$https);
		$this->setPathPrefix($pathPrefix);
	}

	/**
	 * Write a new file.
	 *
	 * @param string $path
	 * @param string $contents
	 * @param Config $config   Config object
	 *
	 * @return array|false false on failure file meta data on success
	 */
	public function write($path, $contents, Config $config) {
		$path = $this->applyPathPrefix($path);
		$params = $config->get('params', null);
		$mime = $config->get('mime', 'application/octet-stream');
		$checkCrc = $config->get('checkCrc', false);
		$list($ret,$code) = $this->ufileSdk->put($path,$contents,['Content-Type'=>$mime]);
	}

	/**
	 * Write a new file using a stream.
	 *
	 * @param string   $path
	 * @param resource $resource
	 * @param Config   $config   Config object
	 *
	 * @return array|false false on failure file meta data on success
	 */
	public function writeStream($path, $resource, Config $config) {
		$path = $this->applyPathPrefix($path);
		$params = $config->get('params', null);
		$mime = $config->get('mime', 'application/octet-stream');
		$checkCrc = $config->get('checkCrc', false);
		$list($ret,$code) = $this->ufileSdk->put($path,$resource,['Content-Type'=>$mime]);
	}

	/**
	 * Update a file.
	 *
	 * @param string $path
	 * @param string $contents
	 * @param Config $config   Config object
	 *
	 * @return array|false false on failure file meta data on success
	 */
	public function update($path, $contents, Config $config) {
		return $this->write($path,$contents,$config);
	}

	/**
	 * Update a file using a stream.
	 *
	 * @param string   $path
	 * @param resource $resource
	 * @param Config   $config   Config object
	 *
	 * @return array|false false on failure file meta data on success
	 */
	public function updateStream($path, $resource, Config $config) {
		return $this->writeStream($path,$resource,$config);
	}

	/**
	 * Rename a file.
	 *
	 * @param string $path
	 * @param string $newpath
	 *
	 * @return bool
	 */
	public function rename($path, $newpath) {
		throw new \Exception('not supprot');
	}

	/**
	 * Copy a file.
	 *
	 * @param string $path
	 * @param string $newpath
	 *
	 * @return bool
	 */
	public function copy($path, $newpath) {
		throw new \Exception('not supprot');
	}

	/**
	 * Delete a file.
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function delete($path) {
		$path = $this->applyPathPrefix($path);
		return $this->ufileSdk->delete($path);
	}

	/**
	 * Delete a directory.
	 *
	 * @param string $dirname
	 *
	 * @return bool
	 */
	public function deleteDir($dirname) {
		throw new \Exception('not supprot');
	}

	/**
	 * Create a directory.
	 *
	 * @param string $dirname directory name
	 * @param Config $config
	 *
	 * @return array|false
	 */
	public function createDir($dirname, Config $config) {
		return ['path' => $dirname];
	}

	/**
	 * Set the visibility for a file.
	 *
	 * @param string $path
	 * @param string $visibility
	 *
	 * @return array|false file meta data
	 */
	public function setVisibility($path, $visibility) {
		return [];
	}
}
