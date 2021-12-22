Contribue to the Project TODO-list 

Clone the Project

See the README.md

Add your contribution to the Project

	You can modify it part as you wish, the codacy notation level must be between A and B.
The final decision to add it, is up to me in the end.

Check the PSR-12 standard

	Its more easy to read your code if its standardized. For do so PHP_codeSniffer is installed by default, run the command 

vendor/bin/phpcs --standard=PSR12 src, in your terminal.

For more information see the documentation PHP_codeSniffer.


How do the pull request

1)	Changing the branch range and destination repository

By default, pull requests are based on the parent repository's default branch.

If the default parent repository isn't correct, you can change both the parent repository and the branch with the drop-down lists. You can also swap your head and base branches with the drop-down lists to establish diffs between reference points. References here must be branch names in your GitHub repository.

2)	Creating the pull request

Create a pull request, use the gh pr create subcommand.
gh pr create

Assign a pull request to me, use the --assignee or -a flags. Important set Your GitHub Displayname.
gh pr create --assignee "@nerpp"

Specify the branch into which you want the pull request merged, use the --base or -B flags. To specify the branch that contains commits for your pull request, use the --head or -H flags.
gh pr create --base my-base-branch --head my-changed-branch

Include a title and body for the new pull request, use the --title and --body flags.
gh pr create --title "The bug is fixed" --body "Everything works again"

Mark a pull request as a draft, use the --draft flag.
gh pr create –draft

Add a labels or milestones to the new pull request, use the --label and --milestone flags
gh pr create --label "bug,help wanted" --milestone octocat-milestone

To add the new pull request to a specific project, use the --project flag.
gh pr create --project projet8-TodoList



