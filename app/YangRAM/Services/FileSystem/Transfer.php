<?php
namespace Files;
use Tangram\ClassLoader;
use Status;
use Request;

final class Transfer extends \AF\ResourceHolders\ResourceReceiver_BC {
	public function getClassName(Request $request){
        if($request->LENGTH>3){
            switch ($request->uri_path[3]) {
                case 'img':
                case 'img':
                return 'Image';
                case 'doc':
                case 'txt':
                case 'wav':
                case 'vod':
                return 'Document';
                case "qrc":
                return 'QRCode';
                case 'vrc':
                return 'VerificationCode';
                case 'key':
                case 'has':
                case 'sec':
                return 'Uploader';
            }
            new Status(404, true);//Status::notFound();
        }
        return 'Uploader';
	}

	public function getMethodName(Request $request){
        if($request->LENGTH>3){
            switch ($request->uri_path[3]) {
                case 'doc':
                case 'txt':
                case 'wav':
                case 'vod':
                case 'has':
                case 'sec':
                return $request->uri_path[3];
                case 'img':
                case 'img':
                case "rqc":
                case 'vrc':
                case 'key':
                return 'main';
            }
            new Status(404, true);//Status::notFound();
        }
        return 'main';
	}

	public function getParameters(Request $request){
		return array_slice($request->uri_path, 4);
	}
}
