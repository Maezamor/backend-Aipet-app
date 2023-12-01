<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Image;
use League\Flysystem\Filesystem;
use League\Flysystem\FileAttributes;
use League\Flysystem\UnableToMoveFile;
use Google\Cloud\Storage\StorageClient;
use League\Flysystem\StorageAttributes;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\FilesystemException;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;

class GoogleCloudStorageController extends Controller
{
    private $filesystem;
    private $bucket = 'aipet-storage';

    public function __construct()
    {
        $storageClient = new StorageClient([
            'keyFilePath' => 'C:\Bangkit Google 2023\Project Capstone\backend-Aipet-app\src\credensial.json',
        ]);
        $bucket = $storageClient->bucket($this->bucket);
        $adapter = new GoogleCloudStorageAdapter($bucket);
        $this->filesystem = new Filesystem($adapter);
    }

    public function listFiles()
    {
        $files = $this->filesystem->listContents('/')
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile())
            ->sortByPath()
            ->toArray();

        return view('files.index', ['files' => $files]);
    }

    public function uploadFile(Request $request, $folder = 'dog-image')
    {
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');


            // Read file contents
            $fileContents = $file->getRealPath();

            // Generate a unique name for the image
            $imageName = $folder . '/' . 'image-' . $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
            $stream = fopen($fileContents, 'r+');
            try {
                $this->filesystem->writeStream($imageName, $stream);

                //get url to save databases
                $url = 'https://storage.googleapis.com/' . $this->bucket . '/' . $imageName;
                return  $url;
            } catch (FilesystemException | UnableToWriteFile $e) {
                return 'Could not upload/write the file to Google Storage. Reason: ' . $e->getMessage();
            }
        }

        return  'No file was provided.';
    }

    public function renameFile(Request $request)
    {
        $source = $request->input('source');
        $destination = $request->input('destination');

        if (!$this->filesystem->fileExists($source)) {
            return redirect()->back()->with('error', 'Cannot move/rename ' . $source . ' as it does not exist.');
        }

        try {
            $this->filesystem->move($source, $destination);
            return redirect()->back()->with('success', 'File was successfully moved from ' . $source . ' to ' . $destination . '.');
        } catch (FilesystemException | UnableToMoveFile $e) {
            return redirect()->back()->with('error', 'Could not move/rename the file: ' . $source . '. Reason: ' . $e->getMessage());
        }
    }

    public function deleteFile(Request $request)
    {
        $source = $request->input('source');

        if (!$this->filesystem->fileExists($source)) {
            return redirect()->back()->with('error', 'Cannot delete ' . $source . ' as it does not exist.');
        }

        try {
            $this->filesystem->delete($source);
            return redirect()->back()->with('success', 'File (' . $source . ') was successfully deleted.');
        } catch (FilesystemException | UnableToMoveFile $e) {
            return redirect()->back()->with('error', 'Could not delete the file: ' . $source . '. Reason: ' . $e->getMessage());
        }
    }
}
