use Google\Cloud\Storage\StorageClient;

$client = new StorageClient();
$bucket = $client->bucket('laravel_tomoya'); // 作成したバケット名
$bucket->upload(
    fopen(storage_path('./storage/app/public/test.json'), 'r')
);
