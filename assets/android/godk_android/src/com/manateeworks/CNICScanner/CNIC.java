package com.manateeworks.CNICScanner;

import java.io.UnsupportedEncodingException;

public class CNIC {
	private byte[] rawDat;
	private byte[] bUniqueNo;
	private byte[] bIdNo;
	private byte[] bFamilyNo;
	private byte[] bDateOfBirth;
	private byte[] bName;
	private byte[] bFatherName;
	private byte[] bAddress;
	private byte[] bDistrict;
	private byte[] bCity;
	private byte[] bMISC;
	private String sUniqueNo;
	private String sIdNo;
	private String sFamilyNo;
	private String sDateOfBirth;
	private String sName;
	private String sFatherName;
	private String sfullAddress;
	private String sHouseNo;
	private String sStreet;
	private String sLocality;
	private String sDistrict;
	private String sTown;
	private String sCity;
	private String sMISC;
	
	public CNIC()
	{
		sIdNo="";
		 sFamilyNo="";
		 sDateOfBirth="";
		 sName="";
		 sFatherName="";
		 sfullAddress="";
		 sHouseNo="";
		 sStreet="";
		 sLocality="";
		 sDistrict="";
		 sTown="";
		 sCity="";
	}
	public CNIC(byte []rawData, boolean QR)
	{
		rawDat = rawData;
		try {
			sIdNo = new String(rawData, "UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
	}
	public CNIC(byte [] rawData)
	{
		rawDat = rawData;
		int []ind = new int [11];
		int inc = 0;
		for(int i=0;i<rawData.length;i++)
		{
			if(rawData[i]==13 && rawData[i+1]!=6)
				inc++;
			else 
				ind[inc]++;
		}
		
		int count =0;
		
		bUniqueNo = new byte [ind[count++]];
		if(ind[3] == 6)
			count++;
		
		bIdNo = new byte[ind[count++]];
		bFamilyNo = new byte[ind[count++]];
		bDateOfBirth = new byte[ind[count++]];
		bName = new byte [ind[count++]];
		bFatherName = new byte [ind[count++]];
		bAddress = new byte [ind[count++]];
		bDistrict = new byte [ind[count++]];
		bCity = new byte [ind[count++]];
		bMISC = new byte [ind[count++]];
		int realInd=0,j=0;
		for(int i=0;i<=10;i++){
							
			copyByte(rawData, realInd, j);
			if(i==0 && ind[3]==6)
			{
				realInd+=ind[i]+1;
				realInd+=ind[i+1]+1;
				i++;
			}
			else
				realInd+=ind[i]+1;
			j++;
		}
		
		bName = formatByteData(bName);
		bName = ConvertUTF8(bName);
		bFatherName = formatByteData(bFatherName);
		bFatherName = ConvertUTF8(bFatherName);
		bAddress = formatByteData(bAddress);
		bAddress = ConvertUTF8(bAddress);
		bDistrict = formatByteData(bDistrict);
		bDistrict = ConvertUTF8(bDistrict);
		bCity = formatByteData(bCity);
		bCity = ConvertUTF8(bCity);
		try {
			sUniqueNo = new String(bUniqueNo,"UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		try {
			sIdNo = new String(bIdNo,"UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		try {
			sFamilyNo = new String(bFamilyNo,"UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		try {
			sDateOfBirth = new String(bDateOfBirth,"UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		try {
			sName = new String(bName,"UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		try {
			sFatherName = new String(bFatherName,"UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		try {
			sfullAddress = new String(bAddress,"UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		try {
			sDistrict = new String(bDistrict,"UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		try {
			sCity = new String(bCity,"UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		try {
			sMISC = new String(bMISC,"UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		
		String part1 = sIdNo.substring(0, 5);
		String part2 = sIdNo.substring(5,12);
		String part3 = sIdNo.substring(12,13);
		
		sIdNo = part1+"-"+part2+"-"+part3;
		if(sDateOfBirth.length()==8)
		{
			String dobDay = sDateOfBirth.substring(0,2);
			String dobMon = sDateOfBirth.substring(2,4);
			String dobYear = sDateOfBirth.substring(4);
		
			int date = Integer.parseInt(dobDay);
			int mon = Integer.parseInt(dobMon);
            dobMon = Month(mon);
            sDateOfBirth = Integer.toString(date) + " " + dobMon + " " + dobYear;
		}
		else if(sDateOfBirth.length()>8)
		{
			String[] vals = sDateOfBirth.split("-");
			String dobmon = "";
			int mon = Integer.parseInt(vals[1]);
			dobmon = Month(mon);
			sDateOfBirth = vals[2]+" "+dobmon+" "+vals[0];
		}

        sfullAddress += " "+sDistrict;
	//	ParseAddress();
		
		
	}
	
	private String Month(int num)
	{
		switch(num)
		{
		case 1: return "Jan";
		case 2: return "Feb";
		case 3: return "Mar";
		case 4: return "Apr";
		case 5: return "May";
		case 6: return "Jun";
		case 7: return "Jul";
		case 8: return "Aug";
		case 9: return "Sep";
		case 10: return "Oct";
		case 11: return "Nov";
		case 12: return "Dec";
		}
		return "Jan";
	}
	
	private byte[] formatByteData(byte[] src)
	{
		int numspaces = findSpaces(src);
		byte[] temp = new byte[src.length + numspaces];
		int []indx = returnSpaceIndx(src);
		int find=0,lind=0,lngth=0;
		if(numspaces!=0)
			lind = indx[0];
		else
			lind = src.length;
		System.arraycopy(src,find, temp,find ,lind-find);
		for(int i=1;lind<temp.length-1;i++){
			
			find=lind;
			if(i<indx.length)
				lind = indx[i];
			else
				lind = temp.length-1;
			lngth = lind-find;
			if((temp.length - (find+i))<lngth)
				lngth = temp.length - (find+i);
			System.arraycopy(src,find, temp,find+i ,lngth);
			
			//	System.arraycopy(src,indx[i], temp,indx[i]+1 ,temp.length-indx[i]-1);
			
		}
		for(int i=0;i<temp.length;i+=2){
			byte t1=0;
			if(i+1<temp.length){
				if(temp[i]!= 0 && temp[i+1]!=32){
					t1=temp[i];
					temp[i]=temp[i+1];
					temp[i+1]=t1;
				}
			}
		}
		return temp;

	}
	
	
	private int[] returnSpaceIndx(byte[]src){
		int []res;
		int size = findSpaces(src);
		res = new int[size];
		for(int i=0,j=0;i<src.length && j<size;i+=2)
		{
			if(i+1<src.length){
				if((src[i]==32 && src[i+1]!=6)||(src[i]!=32 && src[i+1]!=6)){
					res[j]=i;
					i--;
					j++;
				}
			}
		}
		return res;
	}
	private int findSpaces(byte[]src){
		int num=0;
		for(int i=0;i<src.length;i+=2)
		{
			if(i+1<src.length){
				if((src[i]==32 && src[i+1]!=6)||(src[i]!=32 && src[i+1]!=6)){
					num++;
					i--;
					}
			}
		}
		return num;
	}
	private void copyByte(byte[] src,int sIndx,int indi){
		int ind=sIndx;
		switch(indi){
		case 0:
			for(int i=0;i<bUniqueNo.length;i++,ind++)
			{bUniqueNo[i]=src[ind];}break;
		case 1:
			for(int i=0;i<bIdNo.length;i++,ind++)
			{bIdNo[i]=src[ind];}break;
		case 2:
			for(int i=0;i<bFamilyNo.length;i++,ind++)
			{bFamilyNo[i]=src[ind];}break;
		case 3:
			for(int i=0;i<bDateOfBirth.length;i++,ind++)
			{bDateOfBirth[i]=src[ind];}break;
		case 4:
			for(int i=0;i<bName.length;i++,ind++)
			{bName[i]=src[ind];}break;
		case 5:
			for(int i=0;i<bFatherName.length;i++,ind++)
			{bFatherName[i]=src[ind];}break;
		case 6:
			for(int i=0;i<bAddress.length;i++,ind++)
			{bAddress[i]=src[ind];}break;
		case 7:
			for(int i=0;i<bDistrict.length;i++,ind++)
			{bDistrict[i]=src[ind];}break;
		case 8:
			for(int i=0;i<bCity.length;i++,ind++)
			{bCity[i]=src[ind];}break;
		case 9:
			for(int i=0;i<bMISC.length;i++,ind++)
			{bMISC[i]=src[ind];}break;
		}
		
	}
	public String getAll(){
		return 	"CNIC no: "+sIdNo+"\n"
				+"Family Id: "+sFamilyNo+"\n"
				+"DOB: "+sDateOfBirth+"\n"
				+"Name: "+sName+"\n"
				+"Father's Name: "+sFatherName+"\n"
				+"House No: "+sHouseNo+"\n"
				+"Street: "+sStreet+"\n"
				+"Locality: "+sLocality+"\n"
				+"City: "+sCity+"\n"
				+"Sub-District : "+ sTown+"\n"
				+"District: "+sDistrict+"\n"
				+"Full Address: "+sfullAddress+"\n";
	}
	public String getsUniqueNo() {
		return sUniqueNo;
	}
	public String getsIdNo() {
		return sIdNo;
	}
	public String getsFamilyNo() {
		return sFamilyNo;
	}
	public String getsDateOfBirth() {
		return sDateOfBirth;
	}
	public String getsName() {
		
		return sName;
	}
	public String getFatherName() {
				
		return sFatherName;
	}
	public String getFullAddress() {
		return sfullAddress;
	}
	public String getDistrict() {
		return sDistrict;
	}
	public String getCity() {
		return sCity;
	}
	public String getSd5() {
		return sMISC;
	}
	
	public byte[] getbName() {
		return bName;
	}
	public byte[] getBd1() {
		return bFatherName;
	}
	public byte[] getBd2() {
		return bAddress;
	}
	public byte[] getBd3() {
		return bDistrict;
	}
	public byte[] getBd4() {
		return bCity;
	}
	public byte[] getBd5() {
		return bMISC;
	}
	public byte[] getRawData() {
		return rawDat;
	}
	public void SetsIdNo(String idno) {
		sIdNo=idno;
	}
	public void ParseAddress()
    {
		sfullAddress = sfullAddress.trim();
		String[] addArray = sfullAddress.split("،");
        int addressInd = -1;
        addressInd = sfullAddress.indexOf("مکان نمبر");
        int streetInd = -1;
        streetInd = sfullAddress.indexOf("گلی نمبر");
        int LocalityInd = -1;
        LocalityInd = sfullAddress.indexOf("محلہ");
        int DistrictInd = -1;
        int SubDistrict = -1;
        int chk = sfullAddress.indexOf("تحصیل و ضلع");
        if (chk > 0)
        {
            DistrictInd = sfullAddress.indexOf("تحصیل و ضلع");
            SubDistrict = DistrictInd;
            sDistrict = sfullAddress.substring(DistrictInd, sfullAddress.length());
            sTown = sfullAddress.substring(SubDistrict, sfullAddress.length());
            sDistrict = sDistrict.replace("تحصیل و ضلع", " ");
            sTown = sTown.replace("تحصیل و ضلع", " ");
         }
        else
        {
            DistrictInd = sfullAddress.indexOf("ضلع");
            SubDistrict = sfullAddress.indexOf("تحصیل");
            int len = sfullAddress.length();
            if(DistrictInd >= 0 )
            {
            	sDistrict = sfullAddress.substring(DistrictInd,len);
            	sDistrict = sDistrict.replace("ضلع"," ");
            }
            else
            {
            	sDistrict = addArray[addArray.length-1];//sfullAddress.substring(ind, len);
            }
            if(SubDistrict >= 0 )
            {
            	sTown = sfullAddress.substring(SubDistrict,DistrictInd);
            	sTown = sTown.replace("تحصیل", " ");
            }
            else
            {
            	sTown = addArray[addArray.length-1] ;//sfullAddress.substring(indi,len);
            }
        }

        sDistrict = sDistrict.replace('،',' ');
        sTown = sTown.replace('،', ' ');
        sDistrict = sDistrict.trim();
        sTown = sTown.trim();
        
        
            if (addressInd < 0 && streetInd < 0 && LocalityInd < 0)
            {
                sHouseNo = "";// new String(addArray[i++].toCharArray());
                sStreet = "";// new String(addArray[i++].toCharArray());
                sLocality = "";//new String(addArray[i++].toCharArray());
            }
            else
            {
                if (addressInd > -1)
                {
                	for(int k=0;k<addArray.length;k++)
                	{
                		if(addArray[k].indexOf("مکان نمبر") >=0 )
                		{
		                    sHouseNo = new String(addArray[k].toCharArray());
		                    sHouseNo = sHouseNo.replaceFirst("مکان نمبر", " ");
		                    sHouseNo = sHouseNo.trim();
	                    }
                	}
                }
                else
                {
                    sHouseNo = "";
                }
                if (streetInd > -1)
                {
                	for(int k=0;k<addArray.length;k++)
                	{
                		if(addArray[k].indexOf("گلی نمبر") >=0 )
                		{
		                    sStreet = new String(addArray[k].toCharArray());
		                    sStreet = sStreet.replaceFirst("گلی نمبر", " ");
		                    sStreet = sStreet.trim();
	                    }
                	}
                }
                else
                {
                    sStreet = "";
                }
                if (LocalityInd > -1)
                {
                	for(int k=0;k<addArray.length;k++)
                		if(addArray[k].indexOf("محلہ")>=0)
                    sLocality = new String(addArray[k].toCharArray());
                    sLocality = sLocality.replaceFirst("محلہ", " ");
                    sLocality = sLocality.trim();
                }
                else
                    sLocality = "";
                
            }

        
    }
	
	private byte[] ConvertUTF8(byte [] src)
	{
		byte[] res = new byte[src.length];
		for(int i=0;i<src.length;i+=2)
		{
			if(src[i]==6 && (src[i+1]> 0x00 && src[i+1]< 0x40))//64))
			{
				res[i] = (byte)216;
				res[i+1] = (byte)(src[i+1]+128);
			}
			else if(src[i]==6 && (src[i+1]> 0x3F && src[i+1]< 0x80))
			{
				res[i] = (byte)217;
				res[i+1] = (byte)((src[i+1] - 64)+128);
			}
			else if(src[i]==6 && ((src[i+1]+256) > 127 && (src[i+1]+256) < 192))
			{
				res[i] = (byte)218;
				res[i+1] = (byte)((src[i+1]+256 - 128)+128);
			}
			else if(src[i]==6 && ((src[i+1]+256) > 191 && (src[i+1]+256)<= 255 ))
			{
				res[i] = (byte)219;
				res[i+1] = (byte)((src[i+1]+256-192)+128);
				
			}
			else
			{
				res[i] = src[i];
				res[i+1] = src[i+1];
			}

		}
		
		return res;
	}
	
	
}
