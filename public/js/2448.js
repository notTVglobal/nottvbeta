"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[2448],{2448:(e,t,a)=>{a.r(t),a.d(t,{useTeamStore:()=>n});var i=a(69876),s=a(9680),o=function(){return{id:0,name:"",description:"",slug:"",totalSpots:"",memberSpots:"",teamCreator:[],teamLeader:[],members:[],managers:[],activeShow:[],activeEpisode:[],creators:[],showModal:Boolean,confirmDialog:!1,confirmManagerDialog:!1,selectedManagerName:"",selectedManagerId:0,addManager:!1,removeManager:!1,deleteMemberName:"",deleteMemberId:0,noteEdit:0,note:"",saveNoteProcessing:Boolean,goLiveDisplay:!1,can:[],openComponent:"teamShows"}},n=(0,i.Q_)("teamStore",{state:o,actions:{reset:function(){Object.assign(this,o())},setActiveTeam:function(e){this.id=e.id,this.name=e.name,this.description=e.description,this.slug=e.slug,this.totalSpots=e.totalSpots,this.memberSpots=e.memberSpots},setActiveShow:function(e){this.activeShow=e},setActiveEpisode:function(e){this.activeShow=e},deleteTeamMemberCancel:function(){this.confirmDialog=!1},confirmTeamManagerCancel:function(){this.confirmManagerDialog=!1},deleteTeamMember:function(){s.Inertia.visit(route("teams.removeTeamMember"),{method:"post",data:{user_id:this.deleteMemberId,team_id:this.id,team_slug:this.slug}})},addTeamManager:function(){s.Inertia.visit(route("teams.addTeamManager"),{method:"post",data:{user_id:this.selectedManagerId,team_id:this.id,team_slug:this.slug}}),this.confirmManagerDialog=!1},removeTeamManager:function(){s.Inertia.visit(route("teams.removeTeamManager"),{method:"post",data:{user_id:this.selectedManagerId,team_id:this.id,team_slug:this.slug}}),this.confirmManagerDialog=!1},toggleGoLiveDisplay:function(){this.goLiveDisplay=!this.goLiveDisplay}},getters:{spotsRemaining:function(){return this.totalSpots-this.memberSpots<1?0:this.totalSpots-this.memberSpots}}})}}]);
//# sourceMappingURL=2448.js.map