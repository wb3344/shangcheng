' extensions: ods;odt;sxw
'
' TortoiseSVN Merge script for Open Office Calc files
'
' Copyright (C) 2004-2009 the TortoiseSVN team
' This file is distributed under the same license as TortoiseSVN
'
' Last commit by:
' $Author: XhmikosR $
' $Date: 2013-03-15 18:07:24 +0100 (Fr, 15 Mrz 2013) $
' $Rev: 23997 $
'
' Authors:
' Jonathan Ashley, 2007
' Stefan Küng, 2006, 2009
'
dim objArgs,num,sBaseDoc,sMergedDoc,sTheirDoc,sMyDoc,objScript,word,destination

Set objArgs = WScript.Arguments
num = objArgs.Count
if num < 4 then
    MsgBox "Usage: [CScript | WScript] merge-ods.vbs %merged %theirs %mine %base", vbExclamation, "Invalid arguments"
    WScript.Quit 1
end if

sMergedDoc=objArgs(0)
sTheirDoc=objArgs(1)
sMyDoc=objArgs(2)
sBaseDoc=objArgs(3)

Set objScript = CreateObject("Scripting.FileSystemObject")
If objScript.FileExists(sMyDoc) = False Then
    MsgBox "File " + sMyDoc +" does not exist.  Cannot compare the documents.", vbExclamation, "File not found"
    Wscript.Quit 1
End If
If objScript.FileExists(sTheirDoc) = False Then
    MsgBox "File " + sTheirDoc +" does not exist.  Cannot compare the documents.", vbExclamation, "File not found"
    Wscript.Quit 1
End If
'remove the file write protection
objScript.GetFile(sMyDoc).Attributes = objScript.GetFile(sMyDoc).Attributes And Not 1
objScript.GetFile(sTheirDoc).Attributes = objScript.GetFile(sTheirDoc).Attributes And Not 1

Set objScript = Nothing

On Error Resume Next
'The service manager is always the starting point
'If there is no office running then an office is started
Set objServiceManager= Wscript.CreateObject("com.sun.star.ServiceManager")
If Err.Number <> 0 Then
    Wscript.Echo "You must have OpenOffice installed to perform this operation."
    Wscript.Quit 1
End If

On Error Goto 0
'Create the DesktopSet
Set objDesktop = objServiceManager.createInstance("com.sun.star.frame.Desktop")
'Adjust the paths for OO
sMyDoc=Replace(sMyDoc, "\", "/")
sMyDoc=Replace(sMyDoc, ":", "|")
sMyDoc=Replace(sMyDoc, "%", "%25")
sMyDoc=Replace(sMyDoc, " ", "%20")
sMyDoc=Replace(sMyDoc, "#", "%23")
sMyDoc="file:///"&sMyDoc
sTheirDoc=Replace(sTheirDoc, "\", "/")
sTheirDoc=Replace(sTheirDoc, ":", "|")
sTheirDoc=Replace(sTheirDoc, "%", "%25")
sTheirDoc=Replace(sTheirDoc, " ", "%20")
sTheirDoc=Replace(sTheirDoc, "#", "%23")
sTheirDoc="file:///"&sTheirDoc

'Open the %mine document
Dim oPropertyValue(0)
Set oPropertyValue(0) = objServiceManager.Bridge_GetStruct("com.sun.star.beans.PropertyValue")
oPropertyValue(0).Name = "ShowTrackedChanges"
oPropertyValue(0).Value = true
Set objDocument=objDesktop.loadComponentFromURL(sMyDoc,"_blank", 0, oPropertyValue)

'Set the frame
Set Frame = objDesktop.getCurrentFrame

Set dispatcher=objServiceManager.CreateInstance("com.sun.star.frame.DispatchHelper")

'Execute the comparison
Dispatcher.executeDispatch Frame, ".uno:ShowTrackedChanges", "", 0, oPropertyValue
oPropertyValue(0).Name = "URL"
oPropertyValue(0).Value = sTheirDoc
Dispatcher.executeDispatch Frame, ".uno:CompareDocuments", "", 0, oPropertyValue
