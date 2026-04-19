# Plan: pivot Homebrew older-versions content

This file exists to hand off context from a parallel Claude conversation.
Nelson started a rework of the existing 2023 post in another session, but
mid-rework hit a fresh Homebrew error that exposed how brittle the old
`.rb`-file flow has become. The new plan is below — abandon the rework, leave
the 2023 post mostly alone (with a forward-pointing note), and put the modern
approach into a new post that Nelson will write himself.

## Background

The 2023 post `content/posts/2023-04-18-how-to-install-older-versions-of-homebrew-packages.md`
documents installing older Homebrew packages by:

1. Finding the historical formula `.rb` file on GitHub
2. `curl`ing it locally
3. `HOMEBREW_DEVELOPER=true brew install --formulae <pkg>.rb`

That flow is decaying:

- **Bottle pruning**: old bottles get removed from Homebrew's GitHub Container
  Registry, producing `Error: Couldn't find manifest matching bottle checksum.`
  Workaround: `--build-from-source`.
- **Loose-file regression**: with `--build-from-source` (and possibly other
  paths) on current Homebrew, a loose `.rb` file with no tap context throws
  `NoMethodError: undefined method 'user' for nil` because Homebrew tries to
  read `tap.user` on a nil tap.

Nelson hit both of these in sequence today (2026-04-18) trying to install an
older Hugo version.

## Decision

1. **Abandon the in-flight rework of the 2023 post.** The current uncommitted
   edits add a "Note: use brew extract instead" block at the top. Keep the
   *intent* of that note but restructure it (see below).
2. **Leave the 2023 post for historical purposes** with a clear, prominent
   pointer to a new post on `brew extract` at the top.
3. **Create a new post** covering `brew extract` as the modern approach.
   Nelson is writing this himself — do not draft it for him.

## Why a new post instead of a full rework

- The old post's `.rb`-file flow is genuinely the historical method, not just
  outdated phrasing — the right move is preservation + redirect, not rewrite.
- The 2023 post was already losing traffic, so link-equity preservation is
  nice-to-have, not load-bearing.
- A focused new post on `brew extract` is easier to reason about and ranks on
  its own merits.

## What to do in this conversation

### 1. Revise the in-progress note at the top of the 2023 post

The current draft (uncommitted) reads:

```
Note:
Use `brew extract` instead. It's a newer and better method of installing holder versions of homebrew packages. Specially if you're running into errors such as

```
Error: Couldn't find manifest matching bottle checksum.
```
and
```
Error: An exception occurred within a child process:
  NoMethodError: undefined method 'user' for nil
```
with the process I wrote about here.
```

Suggested cleanup (offer to Nelson, don't apply unilaterally):

- Fix typos: "holder" → "older", "Specially" → "Especially"
- Use the site's admonition shortcode for visual prominence (see other posts
  for the `{{< admonition >}}` pattern already used in this file)
- Link directly to the new post once its slug exists
- Frame as "the method below still works in some cases but is increasingly
  broken; here's why" rather than just "use the other thing"

Do not link to the new post until Nelson has actually published it (or at
least decided on a slug). Use a placeholder he can swap in.

### 2. Do NOT draft the new post

Nelson explicitly wants to write the new post himself. He asked for the
technical details on `brew extract` (provided below for reference), but the
prose is his.

If he asks for editorial feedback on a draft, give the honest editor voice
throughout — see his `feedback_blog_post_critique.md` memory: don't cheerlead
during drafting and only turn critical on review.

## Technical reference: how `brew extract` works

For when Nelson is drafting and wants to double-check details:

- **What it does**: walks Homebrew's git history to find the commit where the
  formula was at the version you ask for, copies that historical formula into
  a tap you provide, renames it to `<pkg>@<version>.rb` (a *versioned*
  formula).
- **Why it avoids the `tap.user` bug**: the formula now lives in a real tap,
  so all the metadata Homebrew expects is populated.
- **Versioned formula coexistence**: `hugo@0.155.2` can coexist with current
  `hugo`. Doesn't auto-link as the default `hugo` command — needs
  `brew unlink hugo && brew link hugo@0.155.2`.
- **Bottles still apply**: old versioned bottles can also be pruned. May need
  `--build-from-source`. Unlike the loose-file path, this works cleanly
  because the formula has tap context.
- **Pin still works**: `brew pin hugo@0.155.2` prevents upgrades.

### One-time setup

```sh
brew tap-new $USER/local
```

Creates a local-only tap at `$(brew --repository)/Library/Taps/$USER/homebrew-local`.
No GitHub remote required.

### Per-version workflow

```sh
brew extract --version=0.155.2 hugo $USER/local
brew install hugo@0.155.2
# optional, if you want it to be the default `hugo`:
brew unlink hugo && brew link hugo@0.155.2
# optional, if you want to prevent upgrades:
brew pin hugo@0.155.2
```

### Things worth covering in the post

- Why this exists / why it's better than the loose-file approach (the two
  errors above are concrete pain points to lead with)
- The one-time `brew tap-new` setup and what a "tap" actually is, briefly
- The extract → install → (link) → (pin) workflow
- The `--build-from-source` fallback when bottles are pruned
- How to remove a versioned formula and clean up the tap if needed:
  `brew uninstall hugo@0.155.2`, optionally `brew untap $USER/local`

### References worth linking

- `brew extract` docs: https://docs.brew.sh/Manpage (search for `extract`)
- `brew tap-new` docs: same manpage
- The PR that introduced `HOMEBREW_DEVELOPER` requirement is already linked
  in the 2023 post and may be worth reusing for context on *why* the loose-file
  flow degraded

## Status checklist

- [ ] Clean up the note at the top of the 2023 post (typos + admonition + placeholder link)
- [ ] Decide on slug for the new post (Nelson)
- [ ] Nelson writes the new post
- [ ] Swap placeholder link in 2023 post for real link
- [ ] Delete this `plan.md` once both posts are in their final state
