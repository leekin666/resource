function audiolist(audioList){ //用于给外部进行对象创建使用
    //插件逻辑对象类 begin
    function Audio(audioList){
        if( !(audioList instanceof Array)  ){
            throw new Error("传参格式错误！");
        }
        var getAudioList = new Array();
        for( var i = 0; i < audioList.length; i++ ){ //从第二个开始
            var curSingle = new audioSingle(audioList[i],i);//初始化多个音频
            getAudioList.push(curSingle.getCurAudio());
        }
        this.getAudioList = function(){
            return getAudioList;
        };

        function audioSingle(data,i){
            var elemlist;
            if( "undefined" !== typeof data.elems){
                elemlist = data.elems.elemlist;//所有操作对象
            }else{
                throw new Error("对象格式错误！");
            }
            //滑块点击拖拽调整百分比。对应调整音频开始时间，或者音量
            var playPauseClick = ("undefined" === typeof data.playPauseClick) ? new Function(): data.playPauseClick;
            var onPlayEvent = ("undefined" === typeof data.onPlayEvent) ? new Function(): data.onPlayEvent;
            var onPlayEnd = ("undefined" === typeof data.onPlayEnd) ? new Function(): data.onPlayEnd;

            var curAudioDom = document.querySelector(elemlist.audio.SELECT_NAME);
            if( $(curAudioDom).length > 0  ){
                curAudioDom.setAttribute("src",data.elems.audioUrl);
                //新增音频对象
                var curAudio = new AudioControler({
                    "elem": curAudioDom,
                    "isAutoPlay": data.elems.isAutoPlay,
                    "source":"",
                    "beginTrack":setmodel(0,data.elems.beginTrack),//音频播放的开始时间
                    "speed":setmodel(1,data.elems.speed),//音频播放的速度
                    "blankTime":setmodel(0,data.elems.blankTime)//留白时间
                });
                //获取音频对象
                this.getCurAudio = function(){
                    return curAudio;
                };
                //设置音频对象
                this.setCurAudio = function(val){
                    curAudio = val;
                };
                //重放或开始播放按钮
                var $btPlay = $(elemlist.btPlay);
                if( $btPlay.length > 0  ){
                    $btPlay.on("click", function(){
                        curAudio.replay();
                    })
                }//重放或开始播放按钮 end =====================================

                //暂停播放按钮切换
                var $btPlayPause = $(elemlist.btPlayPause.SELECT_NAME);
                if( $btPlayPause.length > 0  ){
                    $btPlayPause.on("click", function(){
                        $btPlayPause.blur();//当点击过该元素后,再按空格键的时候就会默认触发点击那个元素的事件，因为focus这时候在这个元素上，故失焦处理
                        $this = $(this).parent();
                        //播放暂停导致的其他操作，比如文字的当前句的播放暂停图片切换
                        playPauseClick($this);
                        if($this.hasClass("jp-pause")){
                            curAudio.pause();
                            $this.removeClass("jp-pause");
                        }else {
                            curAudio.play();
                            $this.addClass("jp-pause");
                            //暂停其他音频
                            for(var j = 0; j < getAudioList.length; j++ ){
                                if(i != j){
                                    getAudioList[j].pause();
                                    $(audioList[j].elems.elemlist.btPlayPause.SELECT_NAME).parent().removeClass("jp-pause");
                                }
                            }
                        }

                    });
                }//暂停播放按钮 end =====================================


                curAudio.onPlay = function ( data ){
                    onPlayEvent(data);
                }//音轨显示 end ===================================================================

                curAudio.onEnd = function (){
                    onPlayEnd(data);
                    $btPlayPause.parent().removeClass("jp-pause");
                    curAudio.setStartTime(data.elems.beginTrack);
                    //curAudio.pause();
                }

            }
        }
    }
    //audio播放器类
    function AudioControler( config ) {
        var isAutoplay = config.isAutoPlay;//是否自动播放
        var audio = config.elem;
        var source = config.source == "" ? audio.currentSrc :config.source;
        var _self = this;
        var isPlay = false;
        var beginTrack = config.beginTrack;
        var speed = config.speed;//全文播放速度
        //var times = config.times;//全文播放次数
        this.onPlay = new Function();
        this.onEnd = new Function();
        this.onReady = new Function();
        init( config.source );
        var allTime;
        function init( src ){

            var spyasi = config.blankTime;
            audio.addEventListener("loadedmetadata", function(){
                audio.currentTime = beginTrack;
                audio.playbackRate = speed;
                allTime = audio.duration;
            })
            audio.addEventListener("canplay", function(){

                if(isAutoplay){
                    audio.play();
                }
                _self.onPlay( {"isplay":isPlay,"now":audio.currentTime - spyasi,"nowPercent": ((audio.currentTime - spyasi) / (allTime - spyasi) * 100)} );

                    clearInterval(calTime);
                    var calTime = setInterval(function(){

                        if( audio.currentTime == allTime ){
                            clearInterval(calTime);
                            isPlay = false;
                        }else{
                            isPlay = true;
                        }
                        //IE9 存在问题 只能用计时器完成任务 audio.addEventListener("timeupdate",function(){});
                        _self.onPlay( {"isplay":isPlay,"now":audio.currentTime - spyasi,"nowPercent": ((audio.currentTime - spyasi) / (allTime - spyasi) * 100),"allTime" : allTime - spyasi });
                    },50)
                        _self.onReady( audio)

            });
                //根据播放次数判断结束后是否继续播放
                audio.addEventListener( "ended" ,function(){
                    _self.onEnd();
                })

            if( src === "" ){
                audio.load();
            }else{
                audio.src = src;
                audio.load();

            }
        }
        //音频对象
        this.getAudioElem = function (){
            return audio;
        }
        //音频总时长,最开始为NaN
        this.getAllTime = function (){
            var durtime = audio.duration;
            if(isNaN(durtime)){
                durtime = 0;
            }
            return durtime;
        }

    }
    var isAudioInit =false;
    if( !isAudioInit ){
        AudioControler.prototype = {
            constractor:AudioControler,
            //播放
            play:function(){
                var _self =  this.getAudioElem();
                _self.play();
            },
            //暂停
            pause:function(){
                var _self =  this.getAudioElem();
                _self.pause();
            },
            //重置
            reload:function(src){
                var _self =  this.getAudioElem();
                _self.pause();
                _self.src = src;
                _self.load();
            },
            //重播
            replay:function(){
                var _self =  this.getAudioElem();
                _self.pause();
                _self.currentTime = 0;
                _self.play();
            },
            //设置音量
            setVolume:function( val ){
                var _self =  this.getAudioElem();
                _self.volume = val/100;
            },
            //获取音量
            getVolume:function(){
                var _self =  this.getAudioElem();
                return _self.volume * 100; //对外界以1-100为范围，而自己使用是0-1
            },
            //获取播放总时长
            getAllTime:function(){
                return this.getAllTime();
            },
            //获取当前播放时间
            getCurTime:function(){
                var _self =  this.getAudioElem();
                return _self.currentTime;
            },
            //设置播放开始时间
            setStartTime:function( val){
                var _self =  this.getAudioElem();
              /*  _self.currentTime = val;*/
            },
            //设置播放开始速度
            setAudioSpeed:function( val ){
                var _self =  this.getAudioElem();
                _self.playbackRate = val;
            }
        };
        isAudioInit = true;
    }//audio播放器类 end =====================================

    //给属性设置初始值
    function setmodel(initVal,val){
        var model =initVal;
        if(val != undefined ){
            model = val;
        }
        return model;
    }
    Audio.prototype = {
        constructor:Audio,  //构造器
    }//插件逻辑对象类 end
    return new Audio(audioList); //返回创建的对象类

}            
