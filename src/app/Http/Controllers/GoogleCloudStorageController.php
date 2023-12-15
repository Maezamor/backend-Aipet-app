<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
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
            'keyFilePath' => 'C:\My-Program Folders\Project Capstone\backend-Aipet-app\src\credensial.json',
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
            
            //perikasi dulu nama gambar tidak boleh ada sepasi
            $nameValidate = $slug = Str::slug($request->name);
            // Generate a unique name for the image
            $imageName = $folder . '/' . 'image-' . $nameValidate . '-' . time() . '.' . $file->getClientOriginalExtension();
            $stream = fopen($fileContents, 'r+');
            try {
                $this->filesystem->writeStream($imageName, $stream);

                //get url to save databases
                $url = 'https://storage.googleapis.com/' . $this->bucket . '/' . $imageName;
                return  $url;
            } catch (FilesystemException | UnableToWriteFile $e) {
                return 500;
            }
        }

        return  404;
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

    public function deleteFile($source)
    {
        $fullUrl = $source;

        // Bagian yang ingin dihapus
        $removePart = 'https://storage.googleapis.com/aipet-storage/';
        
        // Menghapus bagian tertentu dari URL
        $relativeUrl = str_replace($removePart, '', $fullUrl);

        //memeriksan apakah filenya ada di google cloud Storage
        if (!$this->filesystem->fileExists($relativeUrl)) {
            return  404;
        }

        try {
            $this->filesystem->delete($relativeUrl);
            return 200;
        } catch (FilesystemException | UnableToMoveFile $e) {
            return  500;
        }
    }
}
