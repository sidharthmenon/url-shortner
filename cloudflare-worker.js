export default {
	/**
	 * @param {Request} request
	 * @param {Env} env
	 * @param {ExecutionContext} ctx
	 * @returns {Promise<Response>}
	 */
	async fetch(request, env, ctx) {
		const url = new URL(request.url)
  	const path = url.pathname // Get the path part of the URL (e.g., /about)

		if(path){

			const s3Url = `https://ksum-url.s3.ap-south-1.amazonaws.com/urls${path}/index.html`

			const s3Response = await fetch(s3Url)

			// Check if the response is successful
			if (s3Response.ok) {
				// If successful, return the content from AWS S3
				return new Response(await s3Response.text(), {
				headers: {
					'Content-Type': 'text/html',
				},
				})
			} else {
				// If not found in AWS S3, return a custom response or fallback to the original request
				return new Response('Page not found', { status: 404 })
			}

		}

		return new Response('Page not found', { status: 404 })
	},
};
