# /completion-drive - Assumption Control Strategy

## Purpose
Meta-Cognitive strategy to harness completion drive productively through two-tier assumption tracking and specialized agent orchestration, maintaining flow state while ensuring systematic accuracy.
Claude should use this strategy whenever it feels it is missing information or making assumptions mid generation and cannot stop to verify. This includes if you find yourself wishing you had committed to a different implementation part way through.
## Usage
```
/completion-drive [task description]
```

## Core Workflow

### Phase 1: Parallel Domain Planning
- Deploy specialized domain agents in parallel
- Each agent creates detailed plan in `docs/completion_drive_plans/`
- Domain agents mark uncertainties with `PLAN_UNCERTAINTY` tags using this same completion drive methodology
- Focus on their domain expertise, flag cross-domain interfaces

### Phase 2: Plan Synthesis & Integration
- Deploy dedicated **plan synthesis agent** to review all domain plans
- Validate interface contracts between plan segments
- Resolve cross-domain uncertainties where possible
- Produce unified implementation blueprint with:
  - Validated integration points
  - Resolved planning assumptions
  - Remaining uncertainties for implementation phase
  - Risk assessment for unresolved items

### Phase 3: Implementation
- Main agent receives synthesized, pre-validated plan
- Implement at full speed with high confidence
- Mark implementation uncertainties with `COMPLETION_DRIVE` tags
- No cognitive load from plan reconciliation
- Pure focus on code execution

### Phase 4: Systematic Verification
- Deploy verification agents to search for all remaining `COMPLETION_DRIVE` tags
- Validate implementation assumptions
- Cross-reference with original `PLAN_UNCERTAINTY` resolutions
- Fix errors, clean up tags with explanatory comments, once addressed the tag should be removed

### Phase 5: Process Cleanup
- Stop processes cleanly, verify no orphaned instances
- Confirm zero COMPLETION_DRIVE tags remain
- Archive successful assumption resolutions for future reference

## Key Benefits
- **Maintains flow state** - no mental context switching
- **Two-tier assumption control** - catch uncertainties at planning AND implementation
- **Systematic accuracy** - all uncertainties tracked and verified  
- **Better code quality** - assumptions become documented decisions
- **Reduced cognitive load** - synthesis agent handles integration complexity

## Plan Synthesis Agent Responsibilities
- **Interface validation** - Ensure data flows correctly between plan segments
- **Dependency resolution** - Identify cross-domain dependencies individual agents miss
- **Conflict detection** - Catch where different domain plans clash
- **Integration mapping** - Document explicit handoff points between systems
- **Assumption alignment** - Ensure consistent assumptions across all plans


## Command Execution
When you use `/completion-drive [task]`, I will:

1. **Deploy domain planning agents in parallel** → create plan files with PLAN_UNCERTAINTY tags as needed
2. **Deploy plan synthesis agent** → validate, integrate, and resolve cross-domain uncertainties
3. **Receive unified blueprint** → pre-validated plan with clear integration points
4. **Implement** → mark only implementation uncertainties with COMPLETION_DRIVE tags
5. **Deploy verification agents** → validate remaining assumptions systematically
6. **Clean up all tags** → replace with proper explanations and documentation
7. **Clean process environment** → ensure no orphaned processes & verify zero tags remain

## Completion Drive Report
At the end of each session, I'll provide a comprehensive report:

```
COMPLETION DRIVE REPORT
═══════════════════════════════════════
Planning Phase:
  PLAN_UNCERTAINTY tags created: X
  ✅ Resolved by synthesis: X
  ⚠️ Carried to implementation: X

Implementation Phase:  
  COMPLETION_DRIVE tags created: X
  ✅ Correct assumptions: X
  ❌ Incorrect assumptions: X
  
Final Status:
  🧹 All tags cleaned: ✅/❌
  📊 Accuracy rate: X%
═══════════════════════════════════════
```
