function GerarSWF($arquivo,$largura,$altura,$id,$flashvars){
    document.writeln('    <object id="globalnav-object" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="' + $largura + '" height="' + $altura + '" id="' + $id + '" name="' + $id + '">');
    document.writeln('        <param name="movie" value="' + $arquivo + '" />');
    document.writeln('        <param name="FlashVars" value="' + $flashvars + '"  />');
    document.writeln('        <param name="bgcolor" value="#ffffff" />');
    document.writeln('        <param name="menu" value="false" />');
    document.writeln('        <param name="quality" value="high" />');
    document.writeln('        <param name="salign" value="tl" />');
    document.writeln('        <param name="scale" value="noscale" />');
    document.writeln('        <param name="wmode" value="transparent" />');
    document.writeln('        <embed id="globalnav-embed" src="' + $arquivo + '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" flashvars="' + $flashvars + '" bgcolor="#ffffff" menu="false" quality="high" salign="tl" scale="noscale" id="' + $id + '" width="' + $largura + '" height="' + $altura + '"></embed>');
    document.writeln('    </object>');
}
