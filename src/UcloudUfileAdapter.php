<?php
namespace Xujif\UcloudUfileStorage;

use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Adapter\Polyfill\NotSupportingVisibilityTrait;
use League\Flysystem\Adapter\Polyfill\StreamedCopyTrait;
use League\Flysystem\Adapter\Polyfill\StreamedTrait;
use League\Flysystem\Config;
use LogicException;
use Xujif\UcloudUfileSdk\UfileSdk;

class UcloudUfileAdapter extends AbstractAdapter {

	use NotSupportingVisibilityTrait, StreamedTrait, StreamedCopyTrait;
	protected $ufileSdk;

	public function __construct($bucket, $public_key, $secret_key, $suffix = '.ufile.ucloud.cn', $pathPrefix = '', $https = false) {
		$this->ufileSdk = new UfileSdk($bucket, $public_key, $secret_key, $suffix, $https);

		$this->setPathPrefix($pathPrefix);
	}

	public function read($path) {
		$path = $this->applyPathPrefix($path);
		$data = [];
		$data['contents'] = $this->ufileSdk->get($path);
		return $data;
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
		list($ret, $code) = $this->ufileSdk->put($path, $contents, ['Content-Type' => $mime]);
		return $ret;
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
		list($ret, $code) = $this->ufileSdk->put($path, $resource, ['Content-Type' => $mime]);
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
		return $this->write($path, $contents, $config);
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
		return $this->writeStream($path, $resource, $config);
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
		throw new LogicException(get_class($this) . ' does not support ' . __FUNCTION__);
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
	 * Check whether a file exists.
	 *
	 * @param string $path
	 *
	 * @return array|bool|null
	 */
	public function has($path) {
		$path = $this->applyPathPrefix($path);
		return $this->ufileSdk->exists($path);
	}

	/**
	 * List contents of a directory.
	 *
	 * @param string $directory
	 * @param bool   $recursive
	 *
	 * @return array
	 */
	public function listContents($directory = '', $recursive = false) {
		throw new LogicException(get_class($this) . ' does not support ' . __FUNCTION__);
	}

	/**
	 * Get all the meta data of a file or directory.
	 *
	 * @param string $path
	 *
	 * @return array|false
	 */
	public function getMetadata($path) {
		$path = $this->applyPathPrefix($path);
		$this->ufileSdk->meta($path);
	}

	/**
	 * Get all the meta data of a file or directory.
	 *
	 * @param string $path
	 *
	 * @return array|false
	 */
	public function getSize($path) {
		$path = $this->applyPathPrefix($path);
		$size = $this->ufileSdk->size($path);
		return array('size' => $size);
	}

	/**
	 * Get the mimetype of a file.
	 *
	 * @param string $path
	 *
	 * @return array|false
	 */
	public function getMimetype($path) {
		$path = $this->applyPathPrefix($path);
		$mimeType = $this->ufileSdk->mime($path);
		return array('mimetype' => $mimeType);
	}

	/**
	 * Get the timestamp of a file.
	 *
	 * @param string $path
	 *
	 * @return array|false
	 */
	public function getTimestamp($path) {
		$meta = $this->getMetadata($path);
		$timestamp = strtotime($meta['Last-Modified']);
		return array('timestamp' => $timestamp);
	}

}
