HPT = '--请选择--';
HCA="--请选择--,水瓶座,双鱼座,白羊座,金牛座,双子座,巨蟹座,狮子座,处女座,天枰座,天蝎座,射手座,摩羯座";

HCAP=[];
HCAP=HCA.split(",");

function HCAS(){
	this.SelP=document.getElementsByName(arguments[0])[0];
	this.DefP=arguments[1];
	
	HCAS.SetP(this);
}
HCAS.SetP=function(PCA){
	for(i=0;i<HCAP.length;i++){
		HCAPT=HCAPV=HCAP[i];
		if(HCAPT==HPT){
			HCAPV="";
		}
		PCA.SelP.options.add(new Option(HCAPT,HCAPV));
		if(PCA.DefP==HCAPV){
			PCA.SelP[i].selected=true;
		}
	}
};
