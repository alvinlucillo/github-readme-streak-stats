export default async () => {
    const buildHookUrl = Netlify.env.get("BUILD_HOOK_URL");

    if (!buildHookUrl) {
        console.log("BUILD_HOOK_URL is not configured; skipping the scheduled rebuild.");
        return;
    }

    const response = await fetch(buildHookUrl, { method: "POST" });

    if (!response.ok) {
        throw new Error(`Netlify build hook returned ${response.status}.`);
    }

    console.log("Started a new Netlify deploy to refresh streak.svg.");
};

export const config = {
    schedule: "@daily",
};
