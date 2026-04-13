window.onload =function(){
	const $fileInput = $('#file-input');
	const $decompressButton = $('#decompress-button');

	$fileInput.on('change', function () {
		if (this.files.length > 0) {
			$decompressButton.prop('disabled', false);
		} else {
			$decompressButton.prop('disabled', true);
		}
	});

	$decompressButton.on('click', async function () {
		const file = $fileInput[0].files[0];
		if (!file) {
			alert('No file selected.');
			return;
		}

		try {
			const fileContent = await file.arrayBuffer();
			const zip = new JSZip();
			await zip.loadAsync(fileContent);

			for (const fileName in zip.files) {
				const file = zip.files[fileName];
				if (!file.dir) {
					const content = await file.async('text');
					console.log(`File: ${fileName}\nContent:\n${content}\n`);
				}
			}

			alert('Decompression complete. Check console for output.');

		} catch (error) {
			console.error('Error decompressing file:', error);
			alert('Error decompressing file. Check console for details.');

		}
	});
}
