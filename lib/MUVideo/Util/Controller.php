<?php
/**
 * MUVideo.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUVideo
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.7.0 (http://modulestudio.de).
 */

/**
 * Utility implementation class for controller helper methods.
 */
class MUVideo_Util_Controller extends MUVideo_Util_Base_AbstractController
{
    /*
     *
    * this function is to get youtube videos into MUVideo
    *
    */
    public function getYoutubeVideos($channelId = '', $collectionId = 0)
    {
        $dom = ZLanguage::getModuleDomain($this->name);
        $youtubeApi = ModUtil::getVar($this->name, 'youtubeApi');

        // we get collection repository and the relevant collection object
        $collectionRepository = MUVideo_Util_Model::getCollectionRepository();
        $collectionObject = $collectionRepository->selectById($collectionId);

        // we get a movie repository
        $movieRepository = MUVideo_Util_Model::getMovieRepository();
        // we get the videos from youtube
        $api = self::getData("https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=50&channelId=" . $channelId  . "&key=" . $youtubeApi);

        // we decode the jason array to php array
        $videos = json_decode($api, true);

        $where = 'tbl.urlOfYoutube != \'' . DataUtil::formatForStore('') . '\'';
        // we look for movies with a youtube url entered
        $existingYoutubeVideos = $movieRepository->selectWhere($where);

        if ($existingYoutubeVideos && count($existingYoutubeVideos > 0)) {
            foreach ($existingYoutubeVideos as $existingYoutubeVideo) {
                $youtubeId = str_replace('https://www.youtube.com/watch?v=', '', $existingYoutubeVideo['urlOfYoutube']);
                $videoIds[] = $youtubeId;
            }
        }
        
        $serviceManager = ServiceUtil::getManager();
        $entityManager = $serviceManager->getService('doctrine.entitymanager');

        if (is_array($videos['items'])) {

            foreach ($videos['items'] as $videoData) {
                if (isset($videoData['id']['videoId'])) {
                    if (isset($videoIds) && is_array($videoIds)) {
                        if (in_array($videoData['id']['videoId'], $videoIds)) {
                            $fragment = $videoData['id']['videoId'];
                            $where2 = 'tbl.urlOfYoutube LIKE \'%' . $fragment . '\'';
                            $thisExistingVideo = $movieRepository->selectWhere($where2);
                            if(is_array($thisExistingVideo) && count($thisExistingVideo) == 1 && ModUtil::getVar($this->name, 'overrideVars') == 1) {
                                $thisExistingVideoObject = $movieRepository->selectById($thisExistingVideo[0]['id']);

                                $thisExistingVideoObject->setTitle($videoData['snippet']['title']);
                                $thisExistingVideoObject->setDescription($videoData['snippet']['description']);
                                $thisExistingVideoObject->setCollection($collectionObject);
                            
                                $entityManager->flush();
                                LogUtil::registerStatus(__('The video', $dom) . ' ' . $videoData['snippet']['title'] . ' ' . __('was overrided', $dom));
                            }
                            continue;
                        }
                    }
                     
                    $newYoutubeVideo = new MUVideo_Entity_Movie();
                    $newYoutubeVideo->setTitle($videoData['snippet']['title']);
                    $newYoutubeVideo->setDescription($videoData['snippet']['description']);
                    $newYoutubeVideo->setUrlOfYoutube('https://www.youtube.com/watch?v=' . $videoData['id']['videoId']);
                    $newYoutubeVideo->setWidthOfMovie('400');
                    $newYoutubeVideo->setHeightOfMovie('300');
                    $newYoutubeVideo->setWorkflowState('approved');
                    $newYoutubeVideo->setCollection($collectionObject);

                    $entityManager->persist($newYoutubeVideo);
                    $entityManager->flush();
                    LogUtil::registerStatus(__('The video', $dom) . ' ' . $videoData['snippet']['title'] . ' ' . __('was created and put into the collection', $dom) . ' ' . $collectionObject['title']);
                }
            }
        }

        $redirectUrl = ModUtil::url($this->name, 'user', 'display', array('ot' => 'collection', 'id' => $collectionId));
        return System::redirect($redirectUrl);
    }
    
    /*
     *
     * this function is to get youtube playlists into MUVideo
     *
     */
    public function getYoutubePlaylists($channelId = '', $collectionId = 0)
    {
    	$dom = ZLanguage::getModuleDomain($this->name);
    	$youtubeApi = ModUtil::getVar($this->name, 'youtubeApi');
    
    	// we get collection repository and the relevant collection object
    	$collectionRepository = MUVideo_Util_Model::getCollectionRepository();
    	$collectionObject = $collectionRepository->selectById($collectionId);
    
    	// we get a movie repository
    	$playlistRepository = MUVideo_Util_Model::getPlaylistRepository();
        // we get the playlists from youtube
    	$api = self::getData("https://www.googleapis.com/youtube/v3/playlists?part=snippet&maxResult=50&channelId=" . $channelId . "&key=" . $youtubeApi);
    	// we decode the jason array to php array
    	$playlists = json_decode($api, true);
    
    	$where = 'tbl.urlOfYoutubePlaylist != \'' . DataUtil::formatForStore('') . '\'';
    	// we look for movies with a youtube url entered
    	$existingYoutubePlaylists = $playlistRepository->selectWhere($where);
    
    	if ($existingYoutubePlaylists && count($existingYoutubePlaylists > 0)) {
    		foreach ($existingYoutubePlaylists as $existingYoutubePlaylist) {
    			$youtubePLaylistId = str_replace('https://www.youtube.com/playlist?list=', '', $existingYoutubePlaylists['urlOfYoutubePlaylist']);
    			$playlistIds[] = $youtubePlaylistId;
    		}
    	}
    
    	$serviceManager = ServiceUtil::getManager();
    	$entityManager = $serviceManager->getService('doctrine.entitymanager');
    
    	if (is_array($playlists['items'])) {
    
    		foreach ($playlists['items'] as $playlistData) {
    			if (isset($playlistData['id'])) {
    				if (isset($playlistIds) && is_array($playlistIds)) {
    					if (in_array($playlistData['id'], $playlistIds)) {
    						$fragment = $playlistData['id'];
    						$where2 = 'tbl.urlOfYoutube LIKE \'%' . $fragment . '\'';
    						$thisExistingPlaylist = $movieRepository->selectWhere($where2);
    						if(is_array($thisExistingPlaylist) && count($thisExistingPlaylist) == 1 && ModUtil::getVar($this->name, 'overrideVars') == 1) {
    							$thisExistingPlaylistObject = $movieRepository->selectById($thisExistingPlaylist[0]['id']);
    
    							$thisExistingPlaylistObject->setTitle($playlistData['snippet']['title']);
    							$thisExistingPlaylistObject->setDescription($playlistData['snippet']['description']);
    							$thisExistingPlaylistObject->setCollection($collectionObject);
    
    							$entityManager->flush();
    							LogUtil::registerStatus(__('The playlist', $dom) . ' ' . $playlistData['snippet']['title'] . ' ' . __('was overrided', $dom));
    						}
    						continue;
    					}
    				}
    				 
    				$newYoutubePlaylist = new MUVideo_Entity_Playlist();
    				$newYoutubePlaylist->setTitle($playlistData['snippet']['title']);
    				$newYoutubePlaylist->setDescription($playlistData['snippet']['description']);
    				$newYoutubePlaylist->setUrlOfYoutubePlaylist('https://www.youtube.com/playlist?list=' . $playlistData['id']);
    				$newYoutubePlaylist->setWorkflowState('approved');
    				$newYoutubePlaylist->setCollection($collectionObject);
    
    				$entityManager->persist($newYoutubePlaylist);
    				$entityManager->flush();
    				LogUtil::registerStatus(__('The playlist', $dom) . ' ' . $playlistData['snippet']['title'] . ' ' . __('was created and put into the collection', $dom) . ' ' . $collectionObject['title']);
    			}
    		}
    	}
    
    	$redirectUrl = ModUtil::url($this->name, 'user', 'display', array('ot' => 'collection', 'id' => $collectionId));
    	return System::redirect($redirectUrl);
    }

    /*
     *
    * this function is to call a url, for example a youtube call
    */
    public function getData($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
